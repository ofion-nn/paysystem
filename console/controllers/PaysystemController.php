<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 04.09.2019
 * Time: 9:10
 */

namespace console\controllers;

use Yii;
use console\jobs\SaveJob;

/**
 * Class Paysystem
 * @package console\controllers
 */
class PaysystemController extends \yii\console\Controller
{
    private $arResult = [];
    private $arPayment = [];

    public function actionPrepareMessage()
    {
        $countPayment = random_int(1, 10);
        $arResult = [];
        for ($i = 0; $i < $countPayment; $i++) {
            $this->arResult[] = $this->actionGetPayment();
            $this->actionSaveRandResult($this->arPayment);
            Yii::$app->queue->delay(20)->push(new SaveJob([
                'data' => $this->arPayment
                ]));
        }
        return $arResult;
    }

    protected function actionSaveRandResult($content)
    {
        $filePath = realpath(Yii::$app->basePath) . '/uploads/paySystemQueryLog.csv';
        if (file_exists($filePath)) {
            if (is_array($content) && !empty($content)) {
                $file = fopen($filePath, "a");

                    fputcsv($file, $content);

                fclose($file);
            }
        } else {
            $headers = ['query_id', 'sum', 'comission', 'order_number', 'date'];
            $file = fopen($filePath, "w");
            fputcsv($file, $headers);
            fclose($file);
        }
    }


    private function actionGetPayment()
    {
        $this->arPayment = [];
        $this->arPayment['sum'] = random_int(10, 500); // Сумма
        $this->arPayment['commission'] = round($this->actionRandomFloat(0.5, 2), 2);
        $this->arPayment['order_number'] = random_int(1, 20);
    }

    public function actionRandomFloat($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    private function actionCryptoDescription()
    {
        $secretKey = 'First';
        $data = json_encode($this->arResult);
        $encryptedData = Yii::$app->getSecurity()->encryptByPassword($data, $secretKey);
        $W = mb_detect_encoding($encryptedData);
        $hashArr = str_split($encryptedData, 62);
        return $hashArr;
    }


}