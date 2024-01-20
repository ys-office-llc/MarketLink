<?php
class CameraMarketLinkApp extends AppBase
{
  protected $_signinAction = array('account', 'signin');

  // データベースへの接続
  protected function doDbConnection()
  {

    $m = $this->_configure->current['configure']['database']['mysql']['m'];
    $this->_connectModel->connect('m', array(
      'string'   => sprintf('mysql:dbname=%s;host=%s;charset=%s',
                            $m['database'],
                            $m['hostname'],
                            $m['charset']),
      'user'     => $m['username'],
      'password' => $m['password'],
    ));

    $mysql = $this->_configure->current['configure']['database']['mysql'];
    $this->_connectModel->connect('master', array(
      'string'   => sprintf('mysql:dbname=%s;host=%s;charset=%s',
                            $mysql['database'],
                            $mysql['hostname'],
                            $mysql['charset']),
      'user'     => $mysql['username'],
      'password' => $mysql['password'],
    ));
  }

  // ルートディレクトリへのパスを返す
  public function getRootDirectory()
  {

    return dirname(__FILE__);
  }

  // ルーティング定義を返す
  protected function getRouteDefinition()
  {

    return array(
      // AccountControllerクラス関連のルーティング定義
      '/account'
          => array('controller' => 'account',
                   'action'     => 'index'),
      '/account/:action'
          => array('controller' => 'account'),
      '/account/get/:id'
          => array('controller' => 'account',
                   'action'     => 'get'),
      '/account/reset/:operation'
          => array('controller' => 'account',
                   'action'     => 'reset'),
      '/account/reset/:operation/:user_name/:one_time_token'
          => array('controller' => 'account',
                   'action'     => 'reset'),

      // 上記補足ね。
      // :actionに例えばsignupが入っていたら以下のように配列に格納されるため、
      // AccountController::signupActionが実行されるわけですよ。わかる？
      /*
       * array(1) {
       *   ["action"]=>
       *   string(6) "signup"
       * }
       *
       */
      '/'
        => array('controller' => 'potal',
                 'action'     => 'index'),

      '/bids/list/:state_id'
        => array('controller' => 'bids',
                 'action'     => 'list'),
      '/bids/register/:auction_id'
        => array('controller' => 'bids',
                 'action'     => 'register'),
      '/bids/get'
        => array('controller' => 'bids',
                 'action'     => 'get'),
      '/bids/get/:id'
        => array('controller' => 'bids',
                 'action'     => 'get'),
      '/bids/post'
        => array('controller' => 'bids',
                 'action'     => 'post'),

      '/item/list/:state_id'
        => array('controller' => 'item',
                 'action'     => 'list'),
      '/item/get'
        => array('controller' => 'item',
                 'action'     => 'get'),
      '/item/get/:id'
        => array('controller' => 'item',
                 'action'     => 'get'),
      '/item/post'
        => array('controller' => 'item',
                 'action'     => 'post'),

/*
      '/system/research/analysis/:action' => array(
        'controller' => 'systemResearchAnalysis'
      ),
      '/system/research/analysis/get/:id' => array(
        'controller' => 'systemResearchAnalysis',
        'action'     => 'get'
      ),
*/
      '/administrator/host/:action' => array(
        'controller' => 'administratorHost'
      ),
      '/administrator/host/get/:id' => array(
        'controller' => 'administratorHost',
        'action'     => 'get'
      ),

      '/system/item/my/pattern/:action' => array(
        'controller' => 'systemItemMyPattern'
      ),
      '/system/item/my/pattern/get/:id' => array(
        'controller' => 'systemItemMyPattern',
        'action'     => 'get'
      ),

      '/support/contact/:action' => array(
        'controller' => 'supportContact'
      ),

      '/support/account/get/:id' => array(
        'controller' => 'supportAccount',
        'action'     => 'get',
      ),
      '/support/account/:action' => array(
        'controller' => 'supportAccount',
      ),

      '/api/line/bot/message/reply' => array(
        'controller' => 'apiLineBotMessage',
        'action'     => 'reply'
      ),

      '/use/research/analysis/archive/:action' => array(
        'controller' => 'useResearchAnalysisArchive'
      ),
      '/use/research/analysis/archive/get/:id' => array(
        'controller' => 'useResearchAnalysisArchive',
        'action'     => 'get'
      ),
      '/use/research/analysis/archive/get/:id/:year_current/:month_current' => array(
        'controller' => 'useResearchAnalysisArchive',
        'action'     => 'get'
      ),
      '/use/research/analysis/archive/get/:id/:year_current/:month_current/:year_past/:month_past' => array(
        'controller' => 'useResearchAnalysisArchive',
        'action'     => 'get'
      ),
      '/use/research/analysis/archive/get/:id/:year_current' => array(
        'controller' => 'useResearchAnalysisArchive',
        'action'     => 'get'
      ),

      '/research/analysis/:action' => array(
        'controller' => 'researchAnalysis'
      ),
      '/research/analysis/get/:id' => array(
        'controller' => 'researchAnalysis',
        'action'     => 'get'
      ),
      '/research/analysis/get/:store/:id' => array(
        'controller' => 'researchAnalysis',
        'action'     => 'get'
      ),

      '/research/yahoo/auctions/search/:action' => array(
        'controller' => 'researchYahooAuctionsSearch'
      ),
      '/research/yahoo/auctions/search/get/:id' => array(
        'controller' => 'researchYahooAuctionsSearch',
        'action'     => 'get'
      ),
      '/research/yahoo/auctions/search/get/:store/:id' => array(
        'controller' => 'researchYahooAuctionsSearch',
        'action'     => 'get'
      ),

      '/research/free/markets/search/:action' => array(
        'controller' => 'researchFreeMarketsSearch'
      ),
      '/research/free/markets/search/get/:id' => array(
        'controller' => 'researchFreeMarketsSearch',
        'action'     => 'get'
      ),
      '/research/free/markets/watch/:action' => array(
        'controller' => 'researchFreeMarketsWatch'
      ),
      '/research/free/markets/watch/get/:id' => array(
        'controller' => 'researchFreeMarketsWatch',
        'action'     => 'get'
      ),

      '/research/analysis/:action' => array(
        'controller' => 'researchAnalysis'
      ),
      '/research/analysis/get/:id' => array(
        'controller' => 'researchAnalysis',
        'action'     => 'get'
      ),

      '/research/stores/:action' => array(
        'controller' => 'researchStores'
      ),
      '/research/stores/get/:id' => array(
        'controller' => 'researchStores',
        'action'     => 'get'
      ),

      '/research/new/arrival/:action' => array(
        'controller' => 'researchNewArrival'
      ),
      '/research/new/arrival/get/:id' => array(
        'controller' => 'researchNewArrival',
        'action'     => 'get'
      ),
      '/research/new/arrival/get/:store/:id' => array(
        'controller' => 'researchNewArrival',
        'action'     => 'get'
      ),

      '/research/watch/list/list' => array(
        'controller' => 'researchWatchList',
        'action'     => 'list'
      ),

      '/research/watch/list/post' => array(
        'controller' => 'researchWatchList',
        'action'     => 'post'
      ),

      '/import/:action' => array(
        'controller' => 'import'
      ),
      '/import/get/:id' => array(
        'controller' => 'import',
        'action'     => 'get'
      ),

      '/setting/account/:action' => array(
        'controller' => 'settingAccount'
      ),
      '/setting/account/get/:id' => array(
        'controller' => 'settingAccount',
        'action'     => 'get'
      ),

      '/setting/environment/:action' => array(
        'controller' => 'settingEnvironment'
      ),
      '/setting/environment/get/:id' => array(
        'controller' => 'settingEnvironment',
        'action'     => 'get'
      ),

      '/setting/item/my/pattern/:action' => array(
        'controller' => 'settingItemMyPattern'
      ),
      '/setting/item/my/pattern/get/:id' => array(
        'controller' => 'settingItemMyPattern',
        'action'     => 'get'
      ),

      '/setting/item/maker/:action' => array(
        'controller' => 'settingItemMaker'
      ),
      '/setting/item/maker/get/:id' => array(
        'controller' => 'settingItemMaker',
        'action'     => 'get'
      ),

      '/setting/item/category/:action' => array(
        'controller' => 'settingItemCategory'
      ),
      '/setting/item/category/get/:id' => array(
        'controller' => 'settingItemCategory',
        'action'     => 'get'
      ),

      '/setting/item/grade/:action' => array(
        'controller' => 'settingItemGrade'
      ),
      '/setting/item/grade/get/:id' => array(
        'controller' => 'settingItemGrade',
        'action'     => 'get'
      ),

      '/setting/item/description/:action' => array(
        'controller' => 'settingItemDescription'
      ),
      '/setting/item/description/get/:id' => array(
        'controller' => 'settingItemDescription',
        'action'     => 'get'
      ),

      '/setting/item/description/cosmetics/:action' => array(
        'controller' => 'settingItemDescriptionCosmetics'
      ),
      '/setting/item/description/cosmetics/get/:id' => array(
        'controller' => 'settingItemDescriptionCosmetics',
        'action'     => 'get'
      ),

      '/setting/item/description/optics/:action' => array(
        'controller' => 'settingItemDescriptionOptics'
      ),
      '/setting/item/description/optics/get/:id' => array(
        'controller' => 'settingItemDescriptionOptics',
        'action'     => 'get'
      ),

      '/setting/item/description/functions/:action' => array(
        'controller' => 'settingItemDescriptionFunctions'
      ),
      '/setting/item/description/functions/get/:id' => array(
        'controller' => 'settingItemDescriptionFunctions',
        'action'     => 'get'
      ),

      '/setting/item/accessories/:action' => array(
        'controller' => 'settingItemAccessories'
      ),
      '/setting/item/accessories/get/:id' => array(
        'controller' => 'settingItemAccessories',
        'action'     => 'get'
      ),

      '/setting/item/template/yahoo/auctions/:action' => array(
        'controller' => 'settingItemTemplateYahooAuctions'
      ),
      '/setting/item/template/yahoo/auctions/get/:id' => array(
        'controller' => 'settingItemTemplateYahooAuctions',
        'action'     => 'get'
      ),

      '/setting/item/template/ebay/us/:action' => array(
        'controller' => 'settingItemTemplateEbayUs',
      ),
      '/setting/item/template/ebay/us/get/:id' => array(
        'controller' => 'settingItemTemplateEbayUs',
        'action'     => 'get'
      ),

      '/setting/item/template/amazon/jp/:action' => array(
        'controller' => 'settingItemTemplateAmazonJp',
      ),
      '/setting/item/template/amazon/jp/get/:id' => array(
        'controller' => 'settingItemTemplateAmazonJp',
        'action'     => 'get'
      ),

      '/setting/item/condition/yahoo/auctions/:action' => array(
        'controller' => 'settingItemConditionYahooAuctions'
      ),
      '/setting/item/condition/yahoo/auctions/get/:id' => array(
        'controller' => 'settingItemConditionYahooAuctions',
        'action'     => 'get'
      ),

      '/setting/item/condition/ebay/us/:action' => array(
        'controller' => 'settingItemConditionEbayUs',
      ),
      '/setting/item/condition/ebay/us/get/:id' => array(
        'controller' => 'settingItemConditionEbayUs',
        'action'     => 'get'
      ),
    );
  }
}
