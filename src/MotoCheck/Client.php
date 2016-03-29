<?php

namespace MotoCheck;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as HttpClient;

/**
 * Description of Client
 *
 * @author Ericsson33
 */
class Client
{

    /**
     * The KEBS url we are going to query.
     * @var type 
     * The full search url should look like
     * http://www.kebs.org/sqmt/qai/pvoc/search_page.php?content=chassis_number&count=0
     */
    private $url = "";

    public function loadVehicleDetails($chassis_number) {
        $client = new HttpClient([
            'timeout' => 100,
            "verify" => false
        ]);
        $res = $client->request('GET', "http://www.kebs.org/sqmt/qai/pvoc/search_page.php?content=$chassis_number&count=0");
        if ($res->getStatusCode() == 200) {
            // all is well, proceed handling the response
            // echo $res->getHeader('content-type');
            // 'application/json; charset=utf8'

            return $this->handleHtmlResponse($res->getBody()->getContents());
        } else {
            return ["error" => "Status Code:" . $res->getStatusCode()];
        }
    }

    /**
     * This implements the DOMCrawler Componentt to crwaler over the returned HTML response.
     * @param type $reponse
     * @return array vehicle data
     */
    public function handleHtmlResponse($reponse) {
        var_dump($reponse);
        //NOTE: if no matching records found 
        if (mb_strpos(trim(strtolower($reponse)), 'No matching records found') === false) {
            return ["error" => "No results found"];
        } else {
            //just return for now.
            return $reponse;
            //If results are found, a HTML doc will be returned. 
            //We crawl the doc and extract vehicle data.
            $crawler = new Crawler();
            // $crawler->addContent('<html><body><p>Hello World!</p></body></html>');
            //  print $crawler->filter('body > p')->text();
            $crawler->addContent($reponse);
            print $crawler->filter('body > p')->text();
        }
    }

}
