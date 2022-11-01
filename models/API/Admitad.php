<?php

namespace app\models\API;

use Admitad\Api\Api;
use app\models\Link;
use app\models\LinkDomain;
use app\models\Setting;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class Admitad extends \yii\base\Model
{

    public $merchants;

    public $exists_count = 0;
    public $added_count = 0;

    public $added_domains_count = 0;

    private $client_id;
    private $client_secret;

    private $access_token;
    private $refresh_token;

    public function __construct($config = [])
    {
        $this->client_id = Setting::getValue('admitad_client_id');
        $this->client_secret = Setting::getValue('admitad_client_secret');

        $this->access_token = Setting::getValue('admitad_access_token');
        $this->refresh_token = Setting::getValue('admitad_refresh_token');

        if ($this->access_token == '1') {
            $this->getAccessToken();
        }

        parent::__construct($config);
    }

    private function getAccessToken()
    {
//        $base64 = base64_encode($this->client_id . $this->client_secret);
//        $auth_params = [
//            'Authorization: Basic ' . $base64,
//            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
//        ];
//
//        $post_params = [
//            'client_id' => $this->client_id,
//            'grant_type' => 'client_credentials',
//            'scope' => 'public_data websites manage_websites advcampaigns advcampaigns_for_website manage_advcampaigns coupons coupons_for_website'
//        ];
//
//        $ch = curl_init($this->auth_url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $auth_params);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);

        $scope = 'public_data websites manage_websites advcampaigns advcampaigns_for_website manage_advcampaigns coupons coupons_for_website';
        $api = new Api();
        $response = $api->authorizeClient($this->client_id, $this->client_secret, $scope);
        $result = $response->getResult();

        $this->access_token = $result->access_token;
        $this->refresh_token = $result->refresh_token;
        Setting::fastSetValue('admitad_access_token', $result->access_token);
        Setting::fastSetValue('admitad_refresh_token', $result->refresh_token);
    }

    private function refreshToken()
    {
        $api = new Api();
        $result = $api
            ->refreshToken($this->client_id, $this->client_secret, $this->refresh_token)
            ->getResult();

        $this->access_token = $result->access_token;
        $this->refresh_token = $result->refresh_token;
        Setting::fastSetValue('admitad_access_token', $result->access_token);
        Setting::fastSetValue('admitad_refresh_token', $result->refresh_token);
    }


    public function collect(int $countToCheck = 10, int $offset = 0)
    {
        $this->setMerchantList($countToCheck, $offset);
        $this->saveMerchants();
    }

    public function setMerchantList(int $count = 10, int $offset = 0)
    {
        $api = new Api($this->access_token);

        $data = $api->get('/advcampaigns/', array(
            'limit' => $count,
            'offset' => $offset
        ))->getResult();

        $this->merchants = $data->results;
    }

    private function saveMerchants()
    {
        foreach ($this->merchants as $merchant) {
            $link = $this->getLink($merchant['id'], $merchant['site_url']);

            if ($link) {
                $this->exists_count++;
            } else {
                $link = new Link();
                $link->title = $merchant['name'];
//                $link->text = $merchant['description'];
                $link->call_to_action = 'Buy';
                $link->active = 1;
                $link->image = $this->getImageEncoded($merchant['image']);
                $link->admitad_id = $merchant['id'];
                $link->save();

                $this->added_count++;
            }

            if (!$link->hasDomain($merchant['site_url'])) {
                $model = new LinkDomain();
                $model->partner_id = Link::PARTNER_ADMITAD;
                $model->link_id = $link->id;
                $model->name = $merchant['site_url'];
                $model->active = 1;
                $model->affiliate_url = /*$this->generateAffiliateUrl($merchant['id'], $merchant['site_url'])*/ '';
                $model->save();

                $this->added_domains_count++;
            }
        }
    }

    private function getLink($merchant_id, $domain = null)
    {
        $link = Link::find()
            ->where(['admitad_id' => $merchant_id])
            ->limit(1)
            ->one();

        if (!$link) {
            $link = Link::find()
                ->joinWith('domains')
                ->where(['link_domain.name' => $domain])
                ->limit(1)
                ->one();
        }

        return $link;
    }

    private function getImageEncoded($raw_url)
    {
        $path = $raw_url;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        if ($type == 'svg') {
            $type .= '+xml';
        }
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }




}