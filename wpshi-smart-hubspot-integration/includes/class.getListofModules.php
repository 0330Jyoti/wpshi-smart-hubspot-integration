<?php
class GetListofModules{
    
    public function execute($token){
        $url = WPSZI_ZOHOAPIS_URL."/crm/v2/settings/modules";
        
        $curl = curl_init();
        $authtoken = array('Authorization: Zoho-oauthtoken '.$token->access_token);
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => $authtoken,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);

        return $response;
    }
}