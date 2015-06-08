#インストール
##app/composer.json

```json
"repositories": [
      {
          "type": "git",
          "url": "https://github.com/geechsInc/Laravel-kintoneRestAPI.git"
      }
  ],

"require": {
  "geechs/kintone-rest-api": "dev-master"
}

```

##composerのアップデート
```
$ composer update
```
上記でvendor/geechs/kintone-rest-apiが作成される


##サービスプロバイダーの登録
```
// config/app.php

  'providers' => array(
    ・・・・・・・・・,
    'Geechs\KintoneRestApi\KintoneRestApiServiceProvider'

  ),

```

##aliasの登録

```
// config/app.php

  'aliases' => array(
    ・・・・・・・・・,
    'KintoneRestApi' => 'Geechs\KintoneRestApi\Facades\KintoneRestApi',
  ),

);
```

##configの作成・編集
```
$ php artisan vendor:publish
```

config/kintone-rest-api.php が作成されるため、該当項目を編集する。



##routeで呼んでみる
```
// app/Http/routes.php

Route::get('/', function()
{

  return KintoneRestApi::getById($appID, $id);

});
```

##使用方法
wikiをご覧下さい。