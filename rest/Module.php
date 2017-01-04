<?php

namespace rest;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Description of Module
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        $negotiator = new ContentNegotiator([
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                //'text/html' => Response::FORMAT_HTML,
                //'application/xml' => Response::FORMAT_XML,
                '*' => Response::FORMAT_JSON,
            ],
        ]);
        $negotiator->negotiate();

        Yii::$app->getResponse()->on(Response::EVENT_BEFORE_SEND, function($event) {
            $response = $event->sender;
            $response->data = [
                'success' => $response->isSuccessful,
                'statusCode' => $response->statusCode,
                'data' => $response->data,
            ];
            $response->statusCode = 200;
        });
    }
}
