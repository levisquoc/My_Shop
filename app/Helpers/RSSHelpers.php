<?php

namespace App\Helpers;

use App\Facade\Creeper;
use App\Modules\Importrss\Models\rss_links;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Session;

use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;

trait RSSHelpers
{
    public function show_post_twentyFourHour($rss)
    {
        $link = $rss->rss_link;
        $xml = simplexml_load_file($link);

        $categories = Category::GetWithStatus('publish');

        foreach ($xml as $entry) {
            foreach ($entry->item as $item) {

                $desc = explode('<br />', $item->description);         //return to string
                $getdesc = trim(preg_replace('/\s+/', ' ', $desc[1]));
                //using capturing value to get src in tag img
                $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                $items['title'] = (string)$item->title;
                $items['description'] = $getdesc;
                $items['image'] = $src[4];
                $data[] = json_encode($items);
            }
        }
        if (isset($rss)) {
            return view('importrss::RSS.list_post', compact('link', 'entry', 'data', 'rss', 'categories'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function twentyFourHour(Request $request, $rss)
    {
        $checked = $request->arr;
        $category = $request->category ? $request->category : $rss->cate_id;
        $link = $request->link;
        $xml = simplexml_load_file($link);
        $linkrss = $xml->channel->item;
        $i = 1;
        foreach ($linkrss as $item) {
            foreach ($checked as $key => $value) {
                if ($value == $i) {

                    $desc = explode('<br />', $item->description);              //return to string
                    $getdesc = trim(preg_replace('/\s+/', ' ', $desc[1]));      //clear tag(space,br,..)
                    //using capturing value to get src in tag img
                    $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                    //get link post
                    $getlink = $item->link;

                    $content = HtmlDomParser::file_get_html($getlink);

                    if (!$content) {
                        continue;
                    }

                    foreach ($content->find('h1[class="baiviet-title"]') as $title) {

                        $header = $title->innertext;

                    }
                    foreach ($content->find('p[class="baiviet-sapo"]') as $sapo) {

                        $sapo = $sapo->innertext;

                    }
                    foreach ($content->find('div[class="text-conent"]') as $cont) {

                        $cont = $cont->innertext;
                        $cutcont = preg_replace('~<div class="baiviet-bailienquan"(.*?)</div>~Usi', '', $cont);
                    }
                    $fullcontent = $header . $sapo . $cutcont;


                    $data['title'] = (string)$item->title;
                    $data['description'] = $getdesc;
                    $data['image'] = $src[4];
                    $data['content'] = $fullcontent;
                    $data['category_id'] = $category;
                    $data['status'] = 'pending';
                    $data['pos'] = Post::max('pos') + 1;;
                    Post::firstorCreate($data);

                }
            }
            $i++;
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Import Success');
        return response()->json(['status' => 'Import Success'], 200);
    }

    public function show_post_afamily($rss)
    {
        $link = $rss->rss_link;
        $xml = simplexml_load_file($link);

        $categories = Category::GetWithStatus('publish');

        foreach ($xml as $entry) {
            foreach ($entry->item as $item) {
                $description = (string)$item->description;
                $desc = explode('</a>', $description);        //return to string
                $getdesc = strip_tags($desc[1], 'span');    //clear tag span
                //using capturing value to get src in tag img
                $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                $items['title'] = (string)$item->title;
                $items['description'] = $getdesc;
                $items['image'] = $src[4];
                $data[] = json_encode($items);
            }
        }
        if (isset($rss)) {
            return view('importrss::RSS.list_post', compact('link', 'entry', 'data', 'rss', 'categories'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function afamily(Request $request, $rss)
    {
        $checked = $request->arr;
        $category = $request->category ? $request->category : $rss->cate_id;
        $link = $request->link;
        $xml = simplexml_load_file($link);
        $linkrss = $xml->channel->item;
        $i = 1;
        foreach ($linkrss as $item) {
            foreach ($checked as $key => $value) {
                if ($value == $i) {
                    $description = (string)$item->description;
                    $desc = explode('</a>', $description);        //return to string
                    $getdesc = strip_tags($desc[1], 'span');    //clear tag span
                    //using capturing value to get src in tag img
                    $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                    //get link post
                    $getlink = (string)$item->link;
                    $fixlink = trim(preg_replace('/\s+/', ' ', $getlink));
                    $content = HtmlDomParser::file_get_html($fixlink);
                    if (!$content) {
                        continue;
                    }

                    foreach ($content->find('h1[class="d-title"]') as $title) {
                        $header = $title->innertext;
                    }
                    foreach ($content->find('div[class="detail_content"]') as $content) {
                        $content = $content->innertext;
                    }

                    $fullcontent = $header . $content;

                    $data['title'] = (string)$item->title;
                    $data['description'] = $getdesc;
                    $data['image'] = $src[4];
                    $data['content'] = $fullcontent;
                    $data['category_id'] = $category;
                    $data['status'] = 'pending';
                    $data['pos'] = Post::max('pos') + 1;;
                    Post::firstorCreate($data);
                }
            }
            $i++;
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Import Success');
        return response()->json(['status' => 'Import Success'], 200);
    }

    public function show_post_vnexpress($rss)
    {
        $link = $rss->rss_link;
        $xml = simplexml_load_file($link);

        $categories = Category::GetWithStatus('publish');

        foreach ($xml as $entry) {
            foreach ($entry->item as $item) {

                $description = (string)$item->description;
                $desc = explode('</a>', $description);        //return to string
                $getdesc = strip_tags($desc[1], 'span');    //clear tag span

                //using capturing value to get src in tag img
                $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                $items['title'] = (string)$item->title;
                $items['description'] = $getdesc;
                $items['image'] = $src[4];
                $data[] = json_encode($items);

            }
        }
        if (isset($rss)) {
            return view('importrss::RSS.list_post', compact('link', 'entry', 'data', 'rss', 'categories'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function vnexpress(Request $request, $rss)
    {
        $checked = $request->arr;
        $category = $request->category ? $request->category : $rss->cate_id;
        $link = $request->link;
        $xml = simplexml_load_file($link);
        $linkrss = $xml->channel->item;
        $i = 1;
        foreach ($linkrss as $item) {
            foreach ($checked as $key => $value) {
                if ($value == $i) {
                    $description = (string)$item->description;
                    $desc = explode('</a>', $description);        //return to string
                    $getdesc = strip_tags($desc[1], 'span');    //clear tag span
                    //using capturing value to get src in tag img
                    $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                    //get link post
                    $getlink = (string)$item->link;
                    $fixlink = trim(preg_replace('/\s+/', ' ', $getlink));
                    $content = HtmlDomParser::file_get_html($fixlink);
                    if (!$content) {
                        continue;
                    }
                    foreach ($content->find('h1[class="title_news_detail"]') as $title) {
                        $header = $title->innertext;
                    }
                    foreach ($content->find('h2[class="description"]') as $desc_article) {
                        $desc_article = $desc_article->innertext;
                    }
                    foreach ($content->find('h2[class="description"]') as $desc_article) {

                    }

                    foreach ($content->find('article[class="content_detail"]') as $content_d) {
                        $content_d = $content_d->innertext;
                    }
                    foreach ($content->find('video[id="media-video"]') as $video) {
                        $video = $video->outertext;
                        dd($video);
                    }
                    foreach ($content->find('div[class="fck_detail"]') as $content_v) {
                        $content_v = $content_v->outertext;
                    }

                    if (isset($content_d)) {
                        $fullcontent = $header . '</br>' . $desc_article . '</br>' . $content_d;
                    } else {
                        $fullcontent = $header . '</br>' . $desc_article . '</br>' . $content_v;
                    }


                    $data['title'] = (string)$item->title;
                    $data['description'] = $getdesc;
                    $data['image'] = $src[4];
                    $data['content'] = $fullcontent;
                    $data['category_id'] = $category;
                    $data['status'] = 'pending';
                    $data['pos'] = Post::max('pos') + 1;;
                    Post::firstorCreate($data);
                }
            }
            $i++;
        }
    }

    public function show_post($rss)
    {
        $link = $rss->rss_link;
        $xml = simplexml_load_file($link);

        $categories = Category::GetWithStatus('publish');

        foreach ($xml as $entry) {
            foreach ($entry->item as $item) {
                $description = (string)$item->description;
                $desc = explode('</a>', $description);        //return to string
                $getdesc = strip_tags($desc[1], 'span');    //clear tag span
                //using capturing value to get src in tag img
                $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                $items['title'] = (string)$item->title;
                $items['description'] = $getdesc;
                $items['image'] = $src[4];
                $data[] = json_encode($items);
            }
        }
        if (isset($rss)) {
            return view('importrss::RSS.list_post', compact('link', 'entry', 'data', 'rss', 'categories'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function dantri(Request $request, $rss)
    {
        $checked = $request->arr;
        $category = $request->category ? $request->category : $rss->cate_id;
        $link = $request->link;
        $xml = simplexml_load_file($link);
        $linkrss = $xml->channel->item;
        $i = 1;
        foreach ($linkrss as $item) {
            foreach ($checked as $key => $value) {
                if ($value == $i) {
                    $description = (string)$item->description;
                    $desc = explode('</a>', $description);        //return to string
                    $getdesc = strip_tags($desc[1], 'span');    //clear tag span
                    //using capturing value to get src in tag img
                    $img = preg_match('/<img(\s+?)([^>]*?)src=(\"|\')([^>\\3]*?)\\3([^>]*?)>/is', $desc[0], $src);

                    //get link post
                    $getlink = (string)$item->link;
                    $content = HtmlDomParser::file_get_html($getlink);

                    if (!$content) {
                        continue;
                    }
                    foreach ($content->find('h1[class="fon31"]') as $title) {
                        $header = $title->innertext;
                    }
                    foreach ($content->find('h2[class="fon33"]') as $sapo) {
                        $sapo = $sapo->innertext;
                        $fixsapo = preg_replace('~<span>(.*?)</span>~Usi', '', $sapo);
                    }
                    foreach ($content->find('div[class="detail-content"]') as $content) {
                        $content = $content->innertext;
                    }

                    $fullcontent = $header . '</br>' . $fixsapo . '</br>' . $content;

                    $data['title'] = (string)$item->title;
                    $data['description'] = $getdesc;
                    $data['image'] = $src[4];
                    $data['content'] = $fullcontent;
                    $data['category_id'] = $category;
                    $data['status'] = 'pending';
                    $data['pos'] = Post::max('pos') + 1;;
                    Post::firstorCreate($data);
                }
            }
            $i++;
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Import Success');
        return response()->json(['status' => 'Import Success'], 200);
    }
}