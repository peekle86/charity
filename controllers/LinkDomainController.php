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
                if ($domain['id']) {
                    $dbDomain = LinkDomain::findOne(['id' => $domain['id']]);
                } else {
                    $dbDomain = new LinkDomain();
                }
                $dbDomain->attributes = $domain;
                if (!$dbDomain->save()) {
                    var_dump($dbDomain->errors);
                }
            }
        }
    }

    public function actionDelete($id)
    {
        $domain = LinkDomain::findOne(['id' => $id]);

        if ($domain && $this->request->isDelete) {
            $domain->delete();
        }
    }

}