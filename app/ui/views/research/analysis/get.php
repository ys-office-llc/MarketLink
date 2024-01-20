<?php $this->setPageTitle('title', 'マーケット検索作成') ?>
<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<ol class="breadcrumb">

  <li>

    <a href="<?php print $this->potal().$base_url; ?>">

      ポータル

    </a>
  </li>
  <li>相場スクリーニング</li>
  <li>マーケット検索</li>

  <li class="active">

  <?php if (isset($research_analysis['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $research_analysis['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      作成

    </a>

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php

      print $this->render(
        'research/analysis/crudd',
        array(
          'param'     => $research_analysis,
          'go_update' => $go_update,
        )
      )

    ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">
            商品名
          </span>
        </th>
        <td class="active">
          <input type="text"
                 id="research_analysis_name"
                 name="name"
                 value="<?php print $research_analysis['name'] ?>"
                 size="96" />
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">アクション</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_analysis['action'] === 'chatwork'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="chatwork"
                     autocomplete="off"
          <?php if ($research_analysis['action'] === 'chatwork'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              ChatWorkへ通知
            </label>

          <?php if ($research_analysis['action'] === 'database'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="database"
                     autocomplete="off"
          <?php if ($research_analysis['action'] === 'database'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              データベースへ登録
            </label>


          <?php if ($research_analysis['action'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="all"
                     autocomplete="off"
          <?php if ($research_analysis['action'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて実行する
            </label>

          <?php if ($research_analysis['action'] === 'do_nothing'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="do_nothing"
                     autocomplete="off"
          <?php if ($research_analysis['action'] === 'do_nothing'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              何もしない
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if (strlen($research_analysis['yahoo_auctions_query_include_everything']) > 0): ?>
          <a href="<?php print $http_query['yahoo']['auctions']['sold'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">
            <span class="text-default">ヤフオク</span>
          </a>

        <?php else: ?>

          <span class="text-primary">ヤフオク</span>

        <?php endif; ?>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">検索キーワード</span>
               </th>
                <td class="active">
        
                  <table class="table table-bordered table-condensed">
                    <tbody>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">すべてを含む</span>
                        </th>
        
                        <td class="active" style="width: 600px">
        
                          <input type="text"
                                 id="yahoo_auctions_query_include_everything"
                                 name="yahoo_auctions_query_include_everything"
                                 value="<?php print $research_analysis['yahoo_auctions_query_include_everything'] ?>"
                                 size="80" />
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">いずれかを含む</span>
                        </th>
        
                        <td class="active" style="width: 600px">
        
                          <input type="text"
                                 id="yahoo_auctions_query_include_either"
                                 name="yahoo_auctions_query_include_either"
                                 value="<?php print $research_analysis['yahoo_auctions_query_include_either'] ?>"
                                 size="80" />
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">含めない</span>
                        </th>
        
                        <td class="active" style="width: 600px">
        
                          <input type="text"
                                 id="yahoo_auctions_query_not_include"
                                 name="yahoo_auctions_query_not_include"
                                 value="<?php print $research_analysis['yahoo_auctions_query_not_include'] ?>"
                                 size="80" />
                        </td>
                      </tr>
        
                    </tbody>
                  </table>
        
                </td>
              </tr>

                </td>
              </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">カテゴリー</span>
        </th>

        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'yahoo_auctions_category_id',
              'values'   => $table_values['research_analysis_category'],
              'selected' => $research_analysis['yahoo_auctions_category_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">価格帯の範囲指定</span>
                </th>
        
                <td class="active">
                  <table class="table table-bordered table-condensed">
        
                    <tbody>
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">下限値</span>
                        </th>
        
                        <td class="text-left active" style="width: 600px">
                          <input type="text"
                                 id="yahoo_auctions_min_price"
                                 name="yahoo_auctions_min_price"
                                 value="<?php print $research_analysis['yahoo_auctions_min_price'] ?>"
                                 size="8" />円
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">上限値</span>
                        </th>
                        <td class="text-left active">
                          <input type="text"
                                 id="yahoo_auctions_max_price"
                                 name="yahoo_auctions_max_price"
                                 value="<?php print $research_analysis['yahoo_auctions_max_price'] ?>"
                                 size="8" />円
                        </td>
                      </tr>
                    </tbody>
        
                  </table>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if (strlen($research_analysis['ebay_us_query_include_everything']) > 0): ?>
          <a href="<?php print $http_query['ebay']['sold'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">eBay</span>

          </a>

        <?php else: ?>

          <span class="text-primary">eBay</span>

        <?php endif; ?>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">検索キーワード</span>
       </th>
        <td class="active">

          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">すべてを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="ebay_us_query_include_everything"
                         name="ebay_us_query_include_everything"
                         value="<?php print $research_analysis['ebay_us_query_include_everything'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">いずれかを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="ebay_us_query_include_either"
                         name="ebay_us_query_include_either"
                         value="<?php print $research_analysis['ebay_us_query_include_either'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">含めない</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="ebay_us_query_not_include"
                         name="ebay_us_query_not_include"
                         value="<?php print $research_analysis['ebay_us_query_not_include'] ?>"
                         size="80" />
                </td>
              </tr>
            </tbody>
          </table>

        </td>
      </tr>

                </td>
              </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">カテゴリー</span>
        </th>

        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'ebay_us_category_id',
              'values'   => $table_values['research_analysis_category_ebay_us'],
              'selected' => $research_analysis['ebay_us_category_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">価格帯の範囲指定</span>
                </th>
        
                <td class="active">
                  <table class="table table-bordered table-condensed">
        
                    <tbody>
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">下限値</span>
                        </th>
        
                        <td class="text-left active" style="width: 600px">
                          <input type="text"
                                 id="ebay_us_min_price"
                                 name="ebay_us_min_price"
                                 value="<?php print $research_analysis['ebay_us_min_price'] ?>"
                                 size="8" />ドル
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">上限値</span>
                        </th>
                        <td class="text-left active">
                          <input type="text"
                                 id="ebay_us_max_price"
                                 name="ebay_us_max_price"
                                 value="<?php print $research_analysis['ebay_us_max_price'] ?>"
                                 size="8" />ドル
                        </td>
                      </tr>
                    </tbody>
        
                  </table>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">

          <?php if (isset($http_query['amazon']['jp']['dp'])): ?>

            <a href="<?php print $http_query['amazon']['jp']['dp'] ?>"
               class="btn btn-primary"
               target="_blank"
               style="width: 150px;">

              Amazon

            </a>

          <?php else: ?>

              Amazon

          <?php endif; ?>

          </span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>JP</p>
                  </span>
                </th>
                <td class="active">

                  <table class="table table-bordered table-condensed">
 
                    <tbody>
 
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">
                            <p>ASIN</p>
                          </span>
                        </th>
                        <td class="active">
                          <input type="text"
                                 name="amazon_jp_asin"
                                 value="<?php print $research_analysis['amazon_jp_asin'] ?>"
                                 size="10" />
 
                        </td>
                      </tr>
 
                    </tbody>
                  </table>

                </td>
              </tr>


            </tbody>

          </table>
        </td>
      </tr>


      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
       </th>

        <td class="active"><?php print $research_analysis['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $research_analysis['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $research_analysis['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $research_analysis['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $research_analysis['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

