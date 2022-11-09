<?php

namespace app\models\API;

use app\models\Link;
use app\models\LinkDomain;
use yii\helpers\Json;

class Adgoal extends \yii\base\Model
{

    public $merchants;

    public $exists_count = 0;
    public $added_count = 0;

    public $added_domains_count = 0;
    public $old_domains_count = 0;
    public $new_domains_count = 0;

    public $time_spent = 0;

    private $base_url = 'https://api.smartredirect.de/api_v2/getAffPrograms.php';
    private $public_key = 'Ddyxt4kHUd';
    private $private_key = 'hJHbMIJ3Ih3V3FNqo11zw5OdTzsjSD5h';

    private $user_hash = 'tkoDLLu5';
    private $panel_hash = 'rMcIPnDNf0';
    private $profile_hash = 'JrqDXkw1';


    public function collect()
    {
        $start_time = microtime(true);

        $this->old_domains_count = LinkDomain::find()->where(['partner_id' => Link::PARTNER_ADGOAL])->count();
        LinkDomain::deleteAll(['partner_id' => Link::PARTNER_ADGOAL]);

        $this->setMerchantsList();
        $this->saveMerchants();

        $this->time_spent = microtime(true) - $start_time;
        $this->new_domains_count = $this->added_domains_count - $this->old_domains_count;
    }

    private function setMerchantsList()
    {
        $unix_time = time();

        $get_params = [
            'p' => $this->public_key,
            'k' => md5($unix_time . $this->private_key),
            'c' => $unix_time,
        ];

        $ch = curl_init($this->base_url . '?' . http_build_query($get_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $array = Json::decode($response);

        $this->merchants = $array;
    }

    private function saveMerchants()
    {
        foreach ($this->merchants as $merchant) {
            if (count($merchant['domains']) < 1) {
                continue;
            }

            $link = $this->getLink($merchant['merchants_id'], $merchant['domains'][0]);

            if ($link) {
                $this->exists_count++;
                $link->adgoal_id = $merchant['merchants_id'];
                $link->save();
            } else {
                $short_name = array_reduce(explode('.', $merchant['domains'][0]), function($carry, $item) {
                    return mb_strlen($carry, 'utf-8') < mb_strlen($item, 'utf-8') ? $item : $carry;
                }, '');

                $link = new Link();
                $link->name = $short_name;
                $link->title = $short_name;
                $link->call_to_action = 'Buy';
                $link->active = 1;
                $link->image = ''/*$this->getImageEncoded($merchant['logo'])*/;
                $link->adgoal_id = $merchant['merchants_id'];
                $link->save();

                $this->added_count++;
            }

            foreach ($merchant['domains'] as $key => $domain) {
                $domain = rtrim($domain, '/');
                $domain = str_replace('http://', '', $domain);
                $domain = str_replace('https://', '', $domain);

                if (!$link->hasDomain($domain, Link::PARTNER_ADGOAL)) {
                    $active = 0;
                    if (!$link->activeDomain && $key == 0) {
                        $active = 1;
                    }

                    $model = new LinkDomain();
                    $model->partner_id = Link::PARTNER_ADGOAL;
                    $model->link_id = $link->id;
                    $model->name = $domain;
                    $model->active = $active;
                    $model->affiliate_url = $this->generateAffiliateUrl($merchant['merchants_id'], $domain);
                    $model->save();

                    $this->added_domains_count++;
                }
            }
        }
    }

    private function getLink($merchant_id, $domain = null)
    {
        $link = Link::find()
            ->where(['adgoal_id' => $merchant_id])
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
        $path = str_replace('/90x45/', '/120x60/', $raw_url);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    private function generateAffiliateUrl($merchant_id, $domain)
    {
        $params = [
            'u' => $this->user_hash,
            'm' => $merchant_id,
            'p' => $this->panel_hash,
            't' => $this->profile_hash,
            's' => 'test',
            'url' => 'http://' . $domain
        ];

        return 'http://www.smartredirect.de/redir/clickGate.php?' . http_build_query($params);
    }

}