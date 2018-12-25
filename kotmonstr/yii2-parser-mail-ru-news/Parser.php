<?php
namespace kotmonstr\parser;

use Yii;
use simple_html_dom;

class Parser
{
    protected $url = "https://mail.ru";
    protected $arrLinks = [];
    protected $arrNews = [];
    protected $limit = 16;
    protected $Iterator = 0;
    protected $p = '';

    public function actionInit()
    {
    }

    public function actionNews()
    {
        $html = file_get_html($this->url);
        $fileNews = Yii::getAlias('@frontend/runtime/fileNews.txt');

        // Find all links
        foreach ($html->find('a') as $link) {
            $this->Iterator++;
            if ($this->Iterator <= $this->limit)
                $arrLinks[] = $link->href;
        }

        var_dump($arrLinks);
        // get data
        $Iterator = 0;
        foreach ($arrLinks as $link) {
            $Iterator++;
            $html2 = file_get_html($link);
            if (!empty($html2)) {
                $arrNews[$Iterator]['Title'] = $html2->find('h1.material_title', 0)->plaintext;
                $arrNews[$Iterator]['Image'] = $html2->find('div.article_img a.zoom_js', 0)->href;
                foreach ($html2->find('.material_content p') as $p) {
                    $this->p .= trim(preg_replace("/ {2,}/", " ", $p->plaintext));
                }
                $arrNews[$Iterator]['Content'] = trim($this->p);
            }
            $this->p = '';
        }

        file_put_contents($fileNews, serialize($arrNews));


    }

}
