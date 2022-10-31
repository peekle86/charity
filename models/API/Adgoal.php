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

    private $base_url = 'https://api.smartredirect.de/api_v2/getAffPrograms.php';
    private $public_key = 'Ddyxt4kHUd';
    private $private_key = 'hJHbMIJ3Ih3V3FNqo11zw5OdTzsjSD5h';

    public function collect(int $countToCheck = 10, int $offset = 0)
    {
        $this->setMerchantsList($countToCheck, $offset);
        $this->saveMerchants();
    }

    private function setMerchantsList(int $count = 10, int  $offset = 0)
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
        $array = array_slice($array, $offset, $count);

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
            } else {
                $short_name = stristr($merchant['domains'][0], '.', true);
                $link = new Link();
                $link->name = $short_name;
                $link->title = $short_name;
                $link->call_to_action = 'Buy';
                $link->active = 1;
                $link->image = $this->getImageEncoded($merchant['logo']);
                $link->adgoal_id = $merchant['merchants_id'];
                $link->save();

                $this->added_count++;
            }

            foreach ($merchant['domains'] as $domain) {
                if (!$link->hasDomain($domain)) {
                    $model = new LinkDomain();
                    $model->partner_id = Link::PARTNER_ADGOAL;
                    $model->link_id = $link->id;
                    $model->name = $domain;
                    $model->affiliate_url = '';
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

        if (!$link) {
            $short_name = stristr($domain, '.', true);
            $link = Link::find()
                ->where(['name' => $short_name])
                ->orWhere(['title' => $short_name])
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

}