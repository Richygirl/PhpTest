<?php ini_set('error_reporting', E_ALL);
    $isField;
    $back = "<p><a href=\"javascript: history.back()\">Назад</a></p>";
    if(!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['email']) && !empty($_POST['city'])){
        $isField = $_POST;
        echo "Спасибо. Ваш запрос принят!";
    }
    else {
        echo "Заполните пустые поля! $back";
        exit;
    }

    $user = array(
        'USER_LOGIN'=>'mery131@yandex.ru', 
        'USER_HASH'=>'ee8d12668c60e1a6bbda4130352093c1'
    );
    $responsible_user_id = 449371;

    $link='https://dlatestov.amocrm.ru/private/api/auth.php?type=json';    
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl);
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl); 
    $Response=json_decode($out,true);


    $leads['add'] = array(
        array(
            'name' => 'testlead',
            'status_id' => 123,
            'responsible_user_id' => $responsible_user_id,
            'custom_fields' => array(
                array(
                    'id' => "609613",
                    'values' => array(
                        array(
                            'value' => $isField['phone'],
                            'enum' => "WORK"
                        )
                    )
                ),
                array(
                    'id' => "607557",
                    'values' => array(
                        array(
                            'value' => $isField['city'],
                            'subtype' => "city"
                        )
                    )
                )
            )
        )
    );

    $link='https://dlatestov.amocrm.ru/api/v2/leads';
    $curl=curl_init(); 
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); 
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl);
    $Response=json_decode($out,true);
    $lead_id = $Response['_embedded']['items'][0]['id'];


    $contacts['add'] = array(
        array(
            'name' => $isField['name'],
            'linked_leads_id' => array($lead_id),
            'responsible_user_id' => $responsible_user_id,
            'custom_fields' => array(
                array(
                    'id' => "181759",
                    'values' => array(
                        array(
                            'value' => $isField['phone'],
                            'enum' => "WORK"
                        )
                    )
                ),
                array(
                    'id' => "181761",
                    'values' => array(
                        array(
                            'value' => $isField['email'],
                            'enum' => "WORK"
                        )
                    )
                ),
                array(
                    'id' => "601287",
                    'values' => array(
                        array(
                            'value' => $isField['city'],
                            'subtype' => "city"
                        )
                    )
                )
            )
        )
    );
   
    $link='https://dlatestov.amocrm.ru/api/v2/contacts';
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($contacts));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $Response=json_decode($out,true);
    $Response=$Response['_embedded']['items'];
    $contact_id = $Response[0]['id'];
    
    $data = array (
        'add' => array (
            0 => array (
                'element_id' => (int)$lead_id,
                'element_type' => 2,
                'note_type' => 4  ,
                'text' => $isField['comment'],
                'responsible_user_id' => $responsible_user_id
            ),
        ),
    );
    $link = "https://dlatestov.amocrm.ru/api/v2/notes";
    $headers[] = "Accept: application/json";

    //Curl options
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-
    undefined/2.0");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
    $out = curl_exec($curl);
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($out,TRUE);


    $tasks['add'] = array(
        array(
            'element_id' => $contact_id,
            'element_type' => 1,
            'task_type'=> 1,
            'text' => "Обработать новый контакт",
            'responsible_user_id' => $responsible_user_id,
            'complete_till_at' => '23:59'
        )
    );

    $link='https://dlatestov.amocrm.ru/api/v2/tasks';
    $curl=curl_init(); 
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($tasks));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $Response=json_decode($out,true);

?>