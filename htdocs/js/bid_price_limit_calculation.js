function separate(number) {

  return String(number).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
}

function calculationYahooAuctionsBidPrice() {

  var margin = document.getElementById('earned_margin');
  var market_price = document.getElementById('yahoo_auctions_market_price');
  var commission_rate = document.getElementById('yahoo_auctions_commission_rate');
  var bid_maximum_price = document.getElementById('yahoo_auctions_bid_maximum_price');

  if (isFinite(margin.value) && margin.value > 0) {

    bid_maximum_price.innerText = separate(Math.ceil(market_price.value * commission_rate.textContent * (1 - margin.value / 100)));
  }
}

function calculationEbayUsBidPrice() {

  var margin = document.getElementById('earned_margin');
  var exchange_usd_jpy = document.getElementById('exchange_usd_jpy');
  var market_price = document.getElementById('ebay_us_market_price');
  var commission_rate = document.getElementById('ebay_us_commission_rate');
  var bid_maximum_price = document.getElementById('ebay_us_bid_maximum_price');

  if (isFinite(margin.value) && margin.value > 0) {

    bid_maximum_price.innerText = separate(Math.ceil(market_price.value * exchange_usd_jpy.value * commission_rate.textContent * (1 - margin.value / 100)));
  }
}

function calculationAmazonJpBidPrice(){

  var margin = document.getElementById('earned_margin');
  var market_price = document.getElementById('amazon_jp_market_price');
  var commission_rate = document.getElementById('amazon_jp_commission_rate');
  var bid_maximum_price = document.getElementById('amazon_jp_bid_maximum_price');

  if (isFinite(margin.value) && margin.value > 0) {

    bid_maximum_price.innerText = separate(Math.ceil(market_price.value * commission_rate.textContent * (1 - margin.value / 100)));
  }
}

function calculation() {

  calculationYahooAuctionsBidPrice();
  calculationEbayUsBidPrice();
  calculationAmazonJpBidPrice();
}

function duplicateEbayUsEndPrice() {

  var start_price = document.getElementById('ebay_us_start_price');
  var end_price = document.getElementById('ebay_us_end_price');

  end_price.value = start_price.value;
}

function duplicateQueryString() {

  var research_analysis_name = document.getElementById('research_analysis_name');
  var yahoo_auctions_query = document.getElementById('yahoo_auctions_query_include_everything');
  var ebay_us_query = document.getElementById('ebay_us_query_include_everything');

  yahoo_auctions_query.value = research_analysis_name.value;
  ebay_us_query.value = research_analysis_name.value;
}

function createGraphArchives() {

  var archives = document.getElementById('archives');
  var graph    = JSON.parse(archives.getAttribute("data-graph-data"));
  var period   = archives.getAttribute("data-graph-period");
  var title    = archives.getAttribute("data-graph-title");

  var indexData = {

    labels: graph.date,
    datasets: [
      {
        type: 'line',
        //凡例
        label: 'ヤフオク指標',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(244,173,66,0.2)",
        //枠線の色
        borderColor: "rgba(244,191,66,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(244,146,66,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBackgroundColor: "rgba(179,181,198,1)",
        //結合点の枠線の色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 15,
        //グラフのデータ
        data: graph.index.yahoo.auctions,
      },
      {
        type: 'line',
        //凡例
        label: 'eBay指標',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(66,72,244,0.8)",
        //枠線の色
        borderColor: "rgba(66,72,244,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(75,192,192,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.index.ebay,
      }
    ]
  };

  var numofData = {

    labels: graph.date,
    datasets: [
      {
        type: 'bar',
        //凡例
        label: 'ヤフオク個数（現在）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor : "rgba(244,191,66,0.9)",
        //枠線の色
        borderColor : "rgba(244,173,66,1)",
        //グラフのデータ
        data: graph.numof.yahoo.auctions.selling,
      },
      {
        type: 'bar',
        //凡例
        label: 'ヤフオク個数（'+period+'）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor : "rgba(244,191,66,0.6)",
        //枠線の色
        borderColor : "rgba(244,173,66,1)",
        //グラフのデータ
        data: graph.numof.yahoo.auctions.sold.m1,
      },
      {
        type: 'bar',
        //凡例
        label: 'eBay個数（現在）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(66,72,244,0.9)",
        //枠線の色
        borderColor: "rgba(66,72,244,1)",
        //グラフのデータ
        data: graph.numof.ebay.active,
      },
      {
        type: 'bar',
        //凡例
        label: 'eBay個数（'+period+'）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor : "rgba(66,72,244,0.6)",
        //枠線の色
        borderColor : "rgba(66,72,244,1)",
        //グラフのデータ
        data: graph.numof.ebay.sold.m1,
      }
    ]
  };

  var priceData = {

    labels: graph.date,
    datasets: [
      {
        type: 'line',
        //凡例
        label: 'ヤフオク（最低）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(244,66,66,0.8)",
        //枠線の色
        borderColor: "rgba(244,66,66,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(244,66,66,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBackgroundColor: "rgba(179,181,198,1)",
        //結合点の枠線の色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 15,
        //グラフのデータ
        data: graph.price.yahoo.auctions.min,
      },
      {
        type: 'line',
        //凡例
        label: 'ヤフオク（最高）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(244,244,66,0.8)",
        //枠線の色
        borderColor: "rgba(244,244,66,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(244,244,66,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.price.yahoo.auctions.max,
      },
      {
        type: 'line',
        //凡例
        label: 'ヤフオク（平均）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(244,167,66,0.8)",
        //枠線の色
        borderColor: "rgba(244,167,66,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(244,167,66,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.price.yahoo.auctions.avg,
      },
      {
        type: 'line',
        //凡例
        label: 'eBay（最低）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(66,244,78,0.8)",
        //枠線の色
        borderColor: "rgba(66,244,78,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(66,244,78,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBackgroundColor: "rgba(179,181,198,1)",
        //結合点の枠線の色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 15,
        //グラフのデータ
        data: graph.price.ebay.min,
      },
      {
        type: 'line',
        //凡例
        label: 'eBay（最高）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(122,66,244,0.8)",
        //枠線の色
        borderColor: "rgba(122,66,244,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(122,66,244,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.price.ebay.max,
      },
      {
        type: 'line',
        //凡例
        label: 'eBay（平均）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(66,203,244,0.8)",
        //枠線の色
        borderColor: "rgba(66,203,244,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(66,203,244,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.price.ebay.avg,
      },
      {
        type: 'line',
        //凡例
        label: 'Amazon Japan（最低）',
        //面の表示
        fill: false,
        //線のカーブ
        lineTension: 0,
        //背景色
        backgroundColor: "rgba(244,66,167,0.8)",
        //枠線の色
        borderColor: "rgba(244,66,167,1)",
        //結合点の枠線の色
        pointBorderColor: "rgba(244,66,167,1)",
        //結合点の背景色
        pointBackgroundColor: "#fff",
        //結合点のサイズ
        pointRadius: 5,
        //結合点のサイズ（ホバーしたとき）
        pointHoverRadius: 8,
        //結合点の背景色（ホバーしたとき）
        pointHoverBorderColor: "rgba(220,220,220,1)",
        //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
        pointHitRadius: 10,
        //グラフのデータ
        data: graph.price.amazon.jp.min,
      }
    ]
  };

  var canvasIndex = document.getElementById('index');
  var chartIndex = new Chart(canvasIndex, {
    type: 'line',  //グラフの種類
    data: indexData,  //表示するデータ
    options: {
      title: {
        display: true,
        text: title,
      },
      //軸の設定
      scales: {
        //縦軸の設定
        yAxes: [{
          //目盛りの設定
          ticks: {
            //開始値を0にする
            beginAtZero: true,
            stepSize: 1,
          }
        }]
      }
    }
  });

  var canvasNumof = document.getElementById('numof');
  var chartNumof = new Chart(canvasNumof, {
    type: 'bar',  //グラフの種類
    data: numofData,  //表示するデータ
    options: {
      title: {
        display: true,
        text: title,
      },
      //軸の設定
      scales: {
        //縦軸の設定
        yAxes: [{
          //目盛りの設定
          ticks: {
            //開始値を0にする
            beginAtZero: true,
            //stepSize: 10,
          }
        }]
      }
    }
  });

  var canvasPrice = document.getElementById('price');
  var chartPrice = new Chart(canvasPrice, {
    type: 'line',  //グラフの種類
    data: priceData,  //表示するデータ
    options: {
      title: {
        display: true,
        text: title,
      },
      //軸の設定
      scales: {
        //縦軸の設定
        yAxes: [{
          //目盛りの設定
          ticks: {
            //開始値を0にする
            //beginAtZero: true,
          }
        }]
      }
    }
  });
}

window.onload = function() {

  var archives = document.getElementById('archives')
  if (archives != null) {

    createGraphArchives();
  }

  var yahoo_auctions_market_price = document.getElementById('yahoo_auctions_market_price')
  if (yahoo_auctions_market_price != null) {
    yahoo_auctions_market_price.onkeyup = calculationYahooAuctionsBidPrice;
  }

  var ebay_us_market_price = document.getElementById('ebay_us_market_price')
  if (ebay_us_market_price != null) {
    ebay_us_market_price.onkeyup = calculationEbayUsBidPrice;
  }

  var amazon_jp_market_price = document.getElementById('amazon_jp_market_price')
  if (amazon_jp_market_price != null) {
    amazon_jp_market_price.onkeyup = calculationAmazonJpBidPrice;
  }

  var earned_margin = document.getElementById('earned_margin')
  if (earned_margin != null) {
    earned_margin.onkeyup = calculation;
  }

  var exchange_usd_jpy = document.getElementById('exchange_usd_jpy')
  if (exchange_usd_jpy != null) {
    exchange_usd_jpy.onkeyup = calculation;
  }
  var ebay_us_start_price = document.getElementById('ebay_us_start_price')
  if (ebay_us_start_price != null) {
    ebay_us_start_price.onkeyup = duplicateEbayUsEndPrice;
  }
  var research_analysis_name = document.getElementById('research_analysis_name')
  if (research_analysis_name != null) {
    research_analysis_name.onkeyup = duplicateQueryString;
  }
}
