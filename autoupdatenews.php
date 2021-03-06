<?php
// Отображать все ошибки или нет//
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once "config.php";
require_once "vk.php";
require_once "vkexception.php";


$vk_config = array(
    'app_id' => '4798482',
    'api_secret' => 'yat6sCVTs6g4D8nCgWSJ',
    'access_token' => $config['token']
);

try {
    $vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret'], $vk_config['access_token']);


    // Получаем список последних 20 новостей //
    $wall = $vk->api('newsfeed.get', array(
        'count' => '10',
        'return_banned' => '0',
    ));

    $repost = $vk->api('wall.repost', array(
        'object' => 'wall' . $wall['response']['items'][0]['source_id'] .'_'. $wall['response']['items'][0]['post_id'],
    ));

    // Выводим ленту //
    echo '<h3>Новости</h3>';
    $i = 99;
    foreach ((array)$wall['response']['items'] as $key => $value) {

        if ($value['post_id'] != null ){
            ?>

            <div class="panel panel-default">
                <div class="panel-heading"><a href="http://vk.com/wall<?= $value['source_id'] ?>_<?= $value['post_id'] ?>" target="_blank">http://vk.com/wall<?= $value['source_id'] ?>_<?= $value['post_id'] ?></a> <br />Вероятность репоста: <span class="badge"><?=$i?>%</span></div>
                <div class="panel-body">
                    <?= $value['text'] ?><hr />
                    <span class="text-muted">ВНИМАНИЕ! Если нет текста, то скорее всего в посте присутствует картинка или другое прикрепление.</span>
                </div>
            </div>
        <?  $i= $i/2;
        }
    }
} catch (VK\VKException $error) {
    echo $error->getMessage();
}