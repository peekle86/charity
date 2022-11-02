<?php

namespace app\controllers;

use app\models\LinkDomain;

class LinkDomainController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionToggleStatus()
    {
        $linkDomainId = $this->request->post('id');
        $status = $this->request->post('active');

        $linkDomain = LinkDomain::findOne(['id' => $linkDomainId]);
        if (!$linkDomain) {
            return $this->asJson([
                'result' => false,
                'message' => 'Link not found'
            ]);
        }

        $link = $linkDomain->link;
        foreach ($link->domains as $domain) {
            $domain->active = 0;
            $domain->save();
        }
        $linkDomain->active = $status;
        $linkDomain->save();

        return $this->asJson([
            'result' => true
        ]);
    }

    public function actionSave()
    {
        $domains = $this->request->post();

        if (is_array($domains)) {
            foreach ($domains as $domain) {
                $dbDomain = LinkDomain::findOne(['id' => $domain['id']]);
                $dbDomain->attributes = $domain;
                $dbDomain->save();
            }
        }
    }

}