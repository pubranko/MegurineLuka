var xhr_request;        //ajaxによるリクエスト保存エリア

/**ajax：郵便番号より住所を検索ボタン押下時、
 * 郵便番号をリクエストとしてサーバーに送る。
 * また、検索結果を住所１〜３へ設定する。
 */
function f_address_get(){

    let postal_code1 = document.getElementsByName("postal_code1")[0].value;
    let postal_code2 = document.getElementsByName("postal_code2")[0].value;

    //ajaxで検索条件をクエリーで送信する。
    xhr_request = new XMLHttpRequest();
    xhr_request.onreadystatechange = f_address_set;
    xhr_request.open("GET",window.location.origin+
        '/addresssearch/?postal_code1='+postal_code1+'&'+'postal_code2='+postal_code2,true);
    xhr_request.send(null);

    /**
    ajaxで取得した値を、住所１〜３へそれぞれ編集する。。
     */
    function f_address_set(){
        if (xhr_request.readyState == 4 && xhr_request.status == 200){
            console.log(xhr_request.responseText);
            let address_json = JSON.parse(xhr_request.responseText);

            let address1_elem = document.getElementsByName("address1")[0];
            let address2_elem = document.getElementsByName("address2")[0];
            let address3_elem = document.getElementsByName("address3")[0];

            if(address_json["result_flg"]=="有り"){
                address1_elem.value = address_json["ken_name"];
                address2_elem.value = address_json["city_name"];
                address3_elem.value = address_json["town_name"];
            }else{
                address1_elem.value = "";
                address2_elem.value = "";
                address3_elem.value = "";
            }
        }
    }
}