<?php $this->setPageTitle('title', 'ヤフオク検索条件作成') ?>
<?php print $this->render('nav', array('' => array())); ?>
<?php if (isset($errors) and count($errors) > 0): ?>
<?php print $this->render('errors', array('errors' => $errors)); ?>
<?php endif; ?>
<?php if (isset($successes) and count($successes) > 0): ?>
<?php print $this->render('successes', array('successes' => $successes)); ?>
<?php endif; ?>
<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crud',
                  array(
                    'param'  => $system_research_analysis)); ?>

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
                 name="name"
                 value="<?php print $system_research_analysis['name'] ?>"
                 size="64" />
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if (strlen($system_research_analysis['yahoo_auctions_query']) > 0): ?>
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
                  <span class="text-primary">
                    <p>検索文字列</p>
                    <p>（先頭にハイフン（-)を付けると除外）</p>
                  </span>
                </th>
                <td class="active">
                  <input type="text"
                         name="yahoo_auctions_query"
                         value="<?php print $system_research_analysis['yahoo_auctions_query'] ?>"
                         size="80" />
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
                                 name="yahoo_auctions_min_price"
                                 value="<?php print $system_research_analysis['yahoo_auctions_min_price'] ?>"
                                 size="8" />円
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">上限値</span>
                        </th>
                        <td class="text-left active">
                          <input type="text"
                                 name="yahoo_auctions_max_price"
                                 value="<?php print $system_research_analysis['yahoo_auctions_max_price'] ?>"
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
        <?php if (strlen($system_research_analysis['ebay_us_query']) > 0): ?>
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
                  <span class="text-primary">
                    <p>検索文字列</p>
                    <p>（先頭にハイフン（-)を付けると除外）</p>
                  </span>
                </th>
                <td class="active">
                  <input type="text"
                         name="ebay_us_query"
                         value="<?php print $system_research_analysis['ebay_us_query'] ?>"
                         size="80" />
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
                                 name="ebay_us_min_price"
                                 value="<?php print $system_research_analysis['ebay_us_min_price'] ?>"
                                 size="8" />ドル
                        </td>
                      </tr>
        
                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">上限値</span>
                        </th>
                        <td class="text-left active">
                          <input type="text"
                                 name="ebay_us_max_price"
                                 value="<?php print $system_research_analysis['ebay_us_max_price'] ?>"
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
          <span class="text-primary">Amazon</span>
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
                                 value="<?php print $system_research_analysis['amazon_jp_asin'] ?>"
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

        <td class="active"><?php print $system_research_analysis['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $system_research_analysis['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $system_research_analysis['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $system_research_analysis['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $system_research_analysis['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

