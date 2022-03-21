php/js Validation Library
====

## Description

- 宣言的な記述で php/js の両方で同じルールを適用してバリデーションを行います
- 単純な検証ではなく、「あの値があれだったらこの値はこれ」のような条件付けも出来ます
- input のレンダリングと統合しているため、値戻しやタグの記述は原則として不要です

## Install

```json
{
    "require": {
        "ryunosuke/chmonos": "dev-master"
    }
}
```

## Demo

```sh
cd /path/to/chmonoce
composer example
# access to "http://hostname:3000"
```

## Usage

### 基本

js チェックを行うのでいくつかの静的ファイルが必要です。 `script` ディレクトリ以下が該当します。

- `polyfill.js`: IE11 用のポリフィルです
- `validator.js`: js チェックのコア js ファイルです
- `validator-error.css`: エラー表示用の css ファイルです
- `validator-error.js`: エラー表示用 js ファイルです

なお、 js チェックのエラー表示は完全に分離しているので比較的容易にすげ替えることが可能です。
上記の下2つのファイルがエラー表示を担当します（この組み込みでは toast を使っています）。これらのファイルを変更することでエラー表示をカスタムできます。

`polyfill.js` はオマケです。一応 IE11 でも動作させたいので検証用として置いています。 IE11 に対応しないなら不要です。
対応する場合でも polyfill.io などの別調達でも大丈夫です（冒頭に必要な機能を列挙してあります）。

php 側では下記に続くようなルールを配列で宣言的に記述して Form インスタンスを作成し、レンダリングすれば `<form>` タグが得られます。
その form で submit して validate メソッドで入力値の検証を行います（js バリデーションは原則として画面内で自動的に行われます）。

Form にはいくつかのオプションがあります。

```php
// Form インスタンスを作成
$form = new Form([/* ルールについては後述 */], [
    'tokenName'         => '',           // CSRF トークンの name 属性を指定します。未指定だと CSRF 対策が無効になります
    'nonce'             => '',           // 生成される script タグの nonce 属性を指定します（CSP 用です。不要なら指定不要です）
    'inputClass'        => Input::class, // UI 要素の検証やレンダリングに使用する Input クラス名を指定します。基本的には指定不要です
    'alternativeSubmit' => true,         // submit 時に サブミットボタンの name や formaction などの属性が有効になります
]);

// POST でバリデーション
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form->validateOrThrow($_POST);
}

// レンダリング
$form->form([/* form の属性*/]); // form 開きタグ
$form->form();                   // form 閉じタグ（引数無しで閉じる）
```

上記が基本的な使い方となります。
example ではほぼすべての機能を使用しているので参考にしてください。

#### ルールの記述

Form のルールには下記のような連想配列を渡します。
大量にありますが、ほとんどはデフォルト値でそれなりに動くので、指定するのはごく一部です（究極的には空配列でも動きます）。

```php
$form = new Form([
    'element_name1' => [
        'title'       => '',         // 画面に表示される際の論理名を指定します
        'condition'   => [],         // 入力条件を配列で指定します（後述）
        'options'     => [],         // checkbox や radio などの選択肢を配列で指定します。キーが値、値が表示文字列です
        'suboptions'  => null,       // options に無い値が来たときの選択肢を指定します（後述）
        'subposition' => 'prepend',  // options と suboptions の結合処理を指定します。append で前方に追加、 prepend で後方に追加、クロージャで任意処理が呼び出されます
        'event'       => ['change'], // js チェックが走るイベント名を指定します（後述）
        'propagate'   => [],         // js チェックのイベントの伝播先を指定します（後述）
        'message'     => [],         // エラーメッセージを指定します（後述）
        'phantom'     => [],         // 疑似データソースを指定します（後述）
        'attribute'   => [],         // その他のカスタムデータです。html レンダリングされる際のタグ属性にもなります
        'inputs'      => [],         // ネスト構造を再帰的に記述します（後述）
       //  'javascript'  => true,       // js チェックを行うかを指定します（非推奨）
        'checkmode'   => [           // サーバー・クライアントサイドのどちらで検証を行うか指定します
            'server' => true,        // server を true にするとサーバーサイドで検証が行われます
            'client' => true,        // client を true にするとクライアントサイドで検証が行われます
        ], 
        'wrapper'     => null,       // input 要素をラップする span class を指定します。未指定だとラップしません
        'invisible'   => false,      // 不可視状態で検証を行うかを指定します
        'ignore'      => false,      // 検証や依存関係の値としては全て有効ですが最終的な結果から除くかを指定します（後述）
        'trimming'    => true,       // 値のトリミングを行うかを指定します
        'ime-mode'    => true,       // ime-mode を自動設定するかを指定します
        'autocond'    => true,       // 一部の入力条件を自動設定するかを指定します（後述）
        'multiple'    => null,       // 複数入力か否かを指定します（後述）
        'dependent'   => true,       // 自動伝播設定を行うかを指定します（後述）
        'pseudo'      => true,       // ダミー hidden を生成するかを指定します（後述）
        // 'default'     => null,       // 値が飛んでこなかった時のデフォルト値を指定します（後述）
    ],
    'element_name2' => [/* 構造は同じ */],
    // ・・・
]);
```

一部、難解なオプションがあるので掘り下げていきます。

##### condition

入力条件を指定します。

指定は原則として `条件クラス => [パラメータ]` です。

```php
$rule = [
    'condition' => [
        'Requires' => [],
        'Decimal'  => [3, 2],
        'Range'    => [-200, 200],
    ],
];
```

とすると「入力必須」で「小数 xxx.yy」で「範囲 -200 ～ 200」を表します。

指定の仕方はいくつかあり、下記は全て正しい指定です。

```php
$rule = [
    'condition' => [
        'Range'           => [-200, 200],     // 上述の条件クラス => [パラメータ]
        Range::class      => [-200, 200],     // FQSEN 指定
        'Range'           => ['max' => 200],  // 名前付きパラメータ指定（未指定引数はデフォルト引数値が使われる）
        'Range(max:200)'  => [/* message */], // 名前付きパラメータのキー埋め込み版（message は後述の message キーと同じ）
        'Range(max:200)'                      // 名前付きパラメータの値埋め込み版
        new Range(-200, 200),                 // インスタンス直接指定
    ],
];
```

基本的には `条件クラス => [パラメータ]` で良いです。後からのハンドリングもしやすいです。
ただ、 「複数の同じ Condition を引数違いで使いたい（`Compare` など）」場合に対応できないため、そういう場合は他の指定方法を選ぶ必要があります。
特に上記の「名前付きパラメータのキー埋め込み版」は同時にメッセージも指定できるため、ある種の状況ではかなり便利な記法になります。

各 Condition のパラメータはソースコードや DocComment を参照してください。
コンストラクタの引数がそのまま使用されます。

##### message

エラーメッセージをカスタムします。

エラーメッセージは「form => input => condition => type => message」という階層構造を持ちます（フォームがインプットを持ち、インプットがコンディションを持ち、コンディションがエラー配列を持ちます）。
この構造の `type => message` 部分を指定します。

```php
$rule = [
    'message' => [
        'RequireInvalidText'       => '入力必須です',
        Requires::INVALID_MULTIPLE => '選択必須です',
    ],
];
```

このようにするとエラーメッセージをカスタムできます。指定しないとデフォルトメッセージが使用されます（一応、それらしいメッセージにはなっています）。
上は直指定、下は定数指定ですが、変更に備えて定数指定を推奨します。

ただし、この指定だと「複数の同じ Condition を引数違いで使いたい（`Compare` など）」場合に全て同じメッセージになってしまいます。
個別でメッセージ変更したい場合はネストした配列を渡すことで `condition => type => message` の階層から指定することが出来ます。

```php
$rule = [
    'message' => [
        'condition1' => [
            'RequireInvalidText'       => '入力必須です1',
            Requires::INVALID_MULTIPLE => '選択必須です1',
        ],
        'condition2' => [
            'RequireInvalidText'       => '入力必須です2',
            Requires::INVALID_MULTIPLE => '選択必須です2',
        ],
    ],
];
```

condition1 や condition2 は `condition` 配列のキーと同じものを指定します。

また、メッセージ中に `%name%` という文字列を埋め込むとクラスの同名フィールド（アンダースコア抜き）で置換されます。
例えば組み込みの `Decimal` Condition は `'整数部分を%int%桁、小数部分を%dec%桁以下で入力してください'` というものがあります。
この中の `%int%` `%dec%` はパラメータで指定した値に置き換えられます。

なお、このキーでエラーメッセージを変更してもその要素のみに影響します。
js の容量削減のため、構造的には「共通メッセージがあり、変更した場合のみ個別メッセージが使われる」という動作になっています。
全体共通メッセージを変えたい場合は大本のクラスを書き換えるか `AbstractCondition::setMessages` を使用します。

エラーメッセージは一切 html エスケープが行われないので注意してください。
エスケープはエラー表示プラグインの仕事と割り切って、本体側ではタッチしない設計です。

##### suboptions

options にない値の選択肢を指定します。

例えばある項目の選択肢として「10: 電話」「20: メール」「30: FAX」がある状況を考えます。
通常時は options に `[10 => '電話', 20 => 'メール', 30 => 'FAX']` を指定していれば良いでしょう。
しかし、改修や仕様変更により optionsから「30: FAX」が無くなったとすると、保持している値は 30 のままであるにも関わらず「30: FAX」という選択肢は消えてしまいます。

そのようなときに suboptions に `[30 => 'FAX']` を指定すれば選択肢と出現させられる上、setAutoInArray の検証に引っ掛けることが出来ます。
つまり、下記のコードは（ほぼ）等価です。

```php
$rule = [
    'condition'  => [
        'InArray' => [10, 20], // 値としては 10, 20 だけを許可
    ],
    'options'    => [10 => '電話', 20 => 'メール', 30 => 'FAX'], // 選択肢としては 30:FAX も表示
];
$rule = [
    'options'    => [10 => '電話', 20 => 'メール'], // 30: FAX がない
    'suboptions' => [30 => 'FAX'],                  // ない場合の選択肢として 30: FAX を与える
];
```

単純に言い換えると「options に加えて現在の値も選択肢として出す」という動作です。

options に存在しない選択肢要素は `validation_invalid` クラスが付与され、デフォルトでは赤字で表示されるようになっています。
なお、suboptions にも存在しない値が来た場合は値がラベルになります。上記でいうと `[30 => '30']` になります。
これは多くの場合望ましくない動作なので値に null を与えると機能自体が無効になります（現在の値の選択肢が表示されなくなる）。

選択肢としてどこに加えるか？ は subposition で指定します。
'append' を指定すると末尾に、 'prepend' を指定すると先頭に加えられます。
クロージャを渡すと (options, currents) でコールされて選択肢を構築できます。

##### event

js バリデーションが走るイベント名を指定します。基本的には change か input の2択でしょう。
特殊な仕様として jQuery の名前空間のように `change.noerror` とドット区切りで noerror を指定するとそのイベントは「エラーになるときには発火しない」という動作になります。
逆に言えば noerror をつけると「エラーが解除されるときのみ発火する」となります。

例えば `['change', 'input.noerror']` とすると「変更時にはエラーになるが入力時にはエラーにならない」という動作になります。
これは入力途中にエラーになると画面がうるさい or 邪魔になる、というよくある動作を回避するためのものです。

メールアドレスを入力しようとして `hogera@` までタイプしてエラーになるのは確かにうるさいです。つまり input をつけることは出来ません。
一方、 `hogera@example..com` の入力ミスを修正するときは `.` を消した時点でエラーが消えるほうが親切でしょう。つまり input をつける必要があります。
そんなとき `['change', 'input.noerror']` とすると望ましい動作を実現することが出来ます。

jQuery の名前空間ライクで意図的に汎用的にしていますが、現在のところ `noerror` のみが対応されています。
ここの識別子は将来的に増える可能性があります。

##### propagate

要素に検証が走ったときに合わせて検証する要素を指定します。

例えば `☑ 必須： [　　　　　　]` のような UI でチェックボックスにチェックを入れたときにテキストフィールドにイベントが伝播するように出来ます。
ただし、ルールからある程度推測して自動設定されるため、明示的に指定する状況はあまりありません。

これに関しては後述の `dependent` を参照してください。

##### phantom

他のフィールドの値を結合して自身の値とします。

例えば [年] [月] [日] の3フィールドがあるとして、自身の値は [年月日] として日付バリデーションをしたい場合です。

```php
$rule = [
    'phantom' => ['%04d/%02d/%02d', 'year', 'month', 'day'],
];
```

このようにすると年月日として構築されます。要するに `vsprintf($phantom[0], ...array_slice($phantom, 1))` された値が自身の値になるということです。

##### inputs

ネスト要素を記述します。

```php
$rule = [
    'inputs' => [
        'child_element_name1' => [/* 構造は同じ */],
        'child_element_name2' => [/* 構造は同じ */],
        // ・・・
    ],
];
```

このようにすると input 要素があたかも階層構造を持つような html が生成されます。
「画面内でボタンを押すと要素セットが追加される」ような状況で使用します。
ただし、かなり複雑になる上 context/template の使用がほぼ必須になります。

なお、ネストできるのは2階層までです。それ以上ネストした場合の動作は未定義です。

##### ignore

最終的な結果から取り除くかを指定します。

Requires や Compare, phantrom などで「最終的な結果としては不要だが、ルールとしては必要」という要素が出てくることがあります。
例えば

- Compare で現在日時と比較したいので `now` 要素が必要
- Requires で内部的に `ID` を持っているかが必要
- Ajax で依存値を投げたい

のような状況です。
これらは依存関係の解決や、他の要素のためだけに存在しているもので、最終的な検証値としては不要なことが多いです。
そんなとき、 ignore: true を指定すれば最終的に validate や validateOrThrow で得られる値から自動的にフィルタされるようになります。

また、この属性には省略属性があり、`@element_name` のように要素名に `@` を付与すれば自動で ignore: true とみなされるようになります。

##### autocond

ルールの記述から condition が自動で設定できるようであれば設定します。
例えば

- options があるときに InArray condition
- maxlength が導出できるときは StringLength condition

が自動設定されます。同種の condition が設定されているときは上書きしません。

この処理は Input クラスの `_setAutoXXX` を検出して自動で呼ばれるようになっています。
言い換えれば `_setAutoHoge` というメソッドを生やすことで condition の自動設定を拡張できます。

どの自動設定を行うかは配列で指定できます。例のように true/false で指定した場合は全設定/全未設定を意味します。

```php
$rule = [
    'autocond' => [
        'InArray'      => true,
        'StringLength' => false,
    ],
];
```

これは前述の「options があるときに InArray condition」だけを設定することを意味します。
また、 Input クラスに `_setAutoHoge` メソッドが存在するときに `['Hoge' => false]` を指定すると Hoge も除外されるようになります。

##### dependent

依存項目を指定します。

`propagate` と対になる設定です。 propagate は「自身**の**イベントを伝播する要素（自身 -> 相手）」を指定しますが、この `dependent` は「自身**へ**イベントを伝播する要素（相手 -> 自身）」を指定します。
あまり違いはありませんが、親から子、子から親の場合で指定の仕方が微妙に異なります。
また、 true にするとルールに基づいて自動で相手の `propagate` にマージされます。

例えば `☑ 必須： [　　　　　　]` のような UI でチェックボックスにチェックを入れたときにテキストフィールドにイベントが伝播するように出来ます。
本来なら `☑ 必須` に対して `propagate` を設定するのが筋ですが、  ` [　　　　　　]` に `Requires(checked)` のような条件が設定されていれば「チェックが入っていれば必須」という関連を自動で導き出すことが出来ます。
true はそれを設定するかしないかのフラグです。

true 単値でも `['elemname', true]` のような配列でも指定できます。これは「elemname と自動設定」を意味します。

##### multiple

値の複数値を許可します。
具体的には

- select: multiple 属性が付きます
- file: multiple 属性が付きます
- 上記も含めて全てのタイプの名前が `name[]` になる

デフォルトは `null` で、ある程度自動設定されます（デフォルト値が配列の場合など）。

##### pseudo

input のレンダリング時に直前に hidden を挿入します。

html における checkbox は未チェックだと値が飛ばないため、常にルールの default 値が使われてしまいます。
このオプションを設定すると未チェック時でも空文字・空配列（multiple 時）が飛ぶようになります。

さらに空文字が飛んだ場合の初期値としてこのキーに指定した値が使われます。
true を指定すると配列の場合は空配列、そうでなければ空文字を指定したのと同等になります。

原則として checkbox 専用ですが、multiple な select にも同様のことが言えるので multiple select も対象です。
その他の要素では単に無視されます。

##### default

値が飛んでこなかった時のデフォルト値を指定します。

明示的に指定するとそれが使用されます。
指定しないとルールに基づいて自動で算出されます。
例えば inputs が指定されていれば `[]` だし、 options が指定されていればその最初の値です。

この自動処理は互換性を保たず変更されるため、可能なら明示的に指定するようにしてください。

「値が飛んでこなかった時」であることに注意してください。 isset でも strlen でもなく、本当になかったときのみに適用されます。
ただし、それだとチェックボックスについては少々都合が悪いため `pseudo` でダミー hidden が生成できます。

pseudo と default の違いは下記となります。

- pseudo: checkbox/select で選択されなかったときの既定値
- default: setValues の初期値、飛んでこなかったときの初期値、template/context での初期値

##### その他のルール

ルールは上記のような既定キー以外でフィルタされたりはしません。
適当なキーで適当に値を渡せば `$input->hoge` で参照可能です。

これは拡張性を考慮してのことですが、将来の verup でキーが被ると互換性が崩れるため、何らかのプレフィックスをつけるなどで対処してください。

#### レンダリング

作成した $form オブジェクトを用いて下記のようにレンダリングします。

```php
// form 開きタグ
$form->form([/* form の属性*/]);
// label タグ
$form->label('element_name', [/* label の属性 */]);
// input タグ
$form->input('element_name', [/* input の属性 */]);
// form 閉じタグ（引数無しで閉じる）
$form->form();
```

属性は基本的に name=value の連想配列を与えるだけです。
特殊な処理として「値が false の場合はその属性はなくなる」というものがあります。
これは `readonly` などの論理属性を想定しています。

form タグの属性はある程度よしなに設定されます。
例えば file 要素があると post な multipart/form-data になったりします。
しかし、レンダリング時に明示的に指定しているときは必ずそれが優先されます。

input タグの属性はいくつか特殊な属性があります（カッコ内はデフォルト属性）。

- 共通
    - name: ルール定義に応じて自動設定されます。指定しても無視されます
    - id: 指定しなかった場合、自動で設定されます id <=> for 関連で使用されます
    - type: html における input type 属性です。textarea や select なども統一して使用できます。指定しなかった場合、ルールに応じて自動で推測します
    - class: 指定したとおりになりますが、 `validatable` は必ず付与されます
- type=checkbox の場合
    - multiple(false): ルール配列の multiple と同じ効果があり、name 属性に `[]` が付与されます
    - labeled(true): 弟要素として label タグを生成します
    - label_attrs([]): labeled が有効な場合の label 側の属性連想配列を指定します
    - format(null): 最終的な html を sprintf します。例えば `<div>%s</div>` として div で囲めます
    - separator(null): 複数要素の場合のセパレータを指定します。例えば `separator => '<br>'` として選択肢を改行区切りにします
- type=radio の場合
    - multiple 以外は checkbox と同じです

label タグの属性は特筆すべきことはありません。
敢えて言うなら for, class は自動設定されます（指定した場合はそれが優先）。

#### バリデーション

上記でレンダリングした form タグで submit を行った先で下記のようにバリデーションします。

```php
// 普通に検証
$isvalid = $form->validate($_POST);
$messages = $form->getMessages();

// 駄目だった場合に例外
$form->validateOrThrow($_POST);
```

上記の通り、2通りの方法があります。

- validate
    - 検証結果を bool で返します
    - エラーメッセージは getMessages で受け取ります
    - 引数は参照渡しなので `$_POST` などを直接渡さないように注意する必要があります
- validateOrThrow
    - 検証して駄目だった場合に `ValidationException` を投げます
    - 返り値として新しい値を返します

基本的には validateOrThrow を推奨します。
参照渡しはハンドリングしにくいし、検証エラーが起こった場合の処理は画一的であり、投げっぱなしの例外をフレームワークなどの共通処理部でハンドリングすればそれで事足りるからです。

なお、参照渡しだったり新しい値を返したりするのは本ライブラリは値の正規化も兼ねるという思想によるものです。
例えばルール外のキーを伏せる、 type=file をファイルパスにする、デフォルト値で埋める、など、「単に値を検証するだけ」では不便であり、その後「正しい値」が得られると便利です。
つまり、「検証するとともに完全に valid な値が得られる」がコンセプトとして存在します。

下記のようにすると condition によらない独自のエラーを追加できます。

```php
// 完全カスタムエラーの追加
$form->error('element_name', 'エラーメッセージ');
```

いずれにせよ、エラーのハンドリングは必要ありません。
validate を通した後の form のレンダリングにエラー表示や値戻しが実装されているので、基本的に何もしなくても OK です。

明示的にエラーが得たい場合は `getMessages` `getFlatMessages` などのメソッドを使います。

### context と template

ルールの `inputs` 指定を行うとネスト構造が表現できます。
`inputs` を設定すると `element[-1][subelement]` のような name 属性で生成され、「ある特定のフィールドに紐づくフィールドセット」が定義できます。
これをレンダリングするには下記の context/template メソッドを使用する必要があります。

なお、下記の記述では断りのない限り

```php
[
    'parent' => [
        'inputs' => [
            'child1' => [
                'title'     => '要素1',
            ],
            'child2' => [
                'title'     => '要素2',
            ],
        ]
    ]
]
```

というルールが記述されているものとします。

#### context

指定した連番に応じてフィールドセットを生成します。
開き/閉じは form と同様「引数ありで開き/引数なしで閉じ」です。

```php
<?= $form->form([]) ?>
    <?= $form->context('parent', null) ?>
    <?= $form->input('child1') ?>
    <?= $form->input('child2') ?>
    <?= $form->context() ?>
    
    <?= $form->context('parent', 1) ?>
    <?= $form->input('child1') ?>
    <?= $form->input('child2') ?>
    <?= $form->context() ?>
<?= $form->form() ?>
```

とすると

```html
<form>
    <input disabled="disabled" name="parent[__index][child1]">
    <input disabled="disabled" name="parent[__index][child2]">
    
    <input name="parent[1][child1]">
    <input name="parent[1][child2]">
</form>
```

のようなタグが生成されます。
context メソッドの第2引数で連番を指定しますが、 null を指定すると `__index` という特別なキーが指定されたとしてレンダリングされます。disabled になっているのも `__index` による効果です。

context メソッドは典型的には後述のように `<template>` タグの内部に配置します。IE11 などで template タグがない場合は単に不可視 div などでも構いません。
そのようにして得られた template タグを `chmonos.birth` に渡すと完全な DOM Node として得られるので、後はそれを追加したい箇所に `appendChild` すれば良いです。

もっと具体的な実際の使い方は下記のようになるでしょう。

```php
<?= $form->form([]) ?>
    <ul id="parent">
    <?php foreach($form->parent->getValue() as $index => $value): ?>
        <?= $form->context('parent', $index) ?>
        <li>
            <?= $form->input('child1') ?>
            <?= $form->input('child2') ?>
        </li>
        <?= $form->context() ?>
    <?php endforeach ?>
    </ul>
    <template id="template" data-vtemplate-name="parent">
        <?= $form->context('parent', null) ?>
        <li>
            <?= $form->input('child1') ?>
            <?= $form->input('child2') ?>
        </li>
        <?= $form->context() ?>
    </template>
    <script>
        document.querySelector('#append-button').addEventListener('click', function() {
            document.querySelector('#parent').appendChild(chmonos.birth(document.querySelector('#template')));
        });
    </script>
<?= $form->form() ?>
```

レンダリング時に設定されている値分のフィールドセットを生成した上で、ボタンを click すると新しく DOM が生成されるようになります。
name や index, イベントなどは birth で設定されるため、上記がそのような動作を満たす最小のコードとなります。

#### template

上記の context メソッドはボイラープレートや重複コードが非常に多くなります。実際のデータレンダリングとテンプレートレンダリングが分離しているためです。
動的要素の使い方はある程度固定化されているため、それをある程度自動化したのが template メソッドです。

```php
<?= $form->form([]) ?>
    <ul>
        <?= $form->template('parent') ?>
        <li>
            <?= $form->input('child1') ?>
            <?= $form->input('child2') ?>
        </li>
        <?= $form->template() ?>
    </ul>
    <script>
        document.querySelector('#append-button').addEventListener('click', function() {
            chmonos.spawn('parent');
        });
    </script>
<?= $form->form() ?>
```

値分の foreach や template 部分がなくなり、非常にスッキリしました。
また、追加部分も単に spawn を呼ぶだけになっています。

それでも生成される html や動作は context メソッドを利用した場合とほぼ同じです。
実際のところ template メソッドは context でのボイラープレートを自動でまとめ上げただけに過ぎません。

あまり複雑なことは出来ませんが、単純に動的要素を追加したいだけなら template の方が便利です。

#### どちらを使うべきか

まず context と template の挙動を一言で述べると

- context: **php でレンダリング**（故に template も吐き出せるので使う側でうまくハンドリングする必要がある）
- template: **js でレンダリング**（故に php サイドでの小回りは効かない）

となります。
適しているシチュエーションとしては「動的追加がない（今あるデータだけでレンダリングしてポコポコ増えない）」場合は context、その逆の場合は template が適しています。

### エラーハンドリング

デフォルトでは condition の設定さえしておけば利用側では何もする必要はありません。

エラー表示をカスタムする場合は要素で `validated` を listen します。
これは検証が完了したタイミングでエラーオブジェクト（どのようなエラーが起こったのか）をイベント引数にして発火します。
基本的に本ライブラリは上記を発火するまでが仕事であり、どのように表示するか？ は管轄外というスタンスです。

なお、検証された要素は下記が設定されます。
これは上記のエラー表示とは無関係で常に行われます。

- validation_error クラスの toggle
- validation_ok クラスの toggle
- errorTypes プロパティの代入

### 名前空間とディレクトリの登録

文字列指定で Condition を指定するときに、標準以外（組み込み以外）の Condition を使用する場合は名前空間とディレクトリの登録が必要です。
下記のようにすると任意の Condition を作成して組み込むことが出来ます。

名前空間とディレクトリは規定のものより優先されます。
つまり、既存同名 Condition の挙動を変えることも可能です。

```php
// 名前空間とディレクトリを登録します
\ryunosuke\chmonos\Condition\AbstractCondition::setNamespace($namespace, $directory);
// 登録したディレクトリを漁って $outdir に javascript コードを生成します
\ryunosuke\chmonos\Condition\AbstractCondition::outputJavascript($outdir);
```

また、 $outdir に `phpjs` というディレクトリがあるとその中にある js ファイルをマージします。
組み込みに無い関数や上書きしたい関数がある場合は `phpjs` に配置します。

### 独自検証の追加

上記の登録で文字列指定で Condition が使えるようになるので、そのディレクトリにその名前空間で `AbstractCondition` を継承した独自クラスを配置します。
基本的には組み込みのクラス（`Uri` あたりがシンプルで良い）を見れば書くべきことは分かると思いますが、いくつか作法があります。

#### getJavascriptCode メソッド

js での検証コードを返します。
返り値内に `// @validationcode:inject` というコメントを含めると下記の `validate` メソッドの中身がインジェクションされ、全体としての js コードになります。

つまり、 `// @validationcode:inject` を入れなければ完全に js コードのみとして動作します。
逆に `// @validationcode:inject` を入れることで変数の設定や事前処理などの「js だけの事前処理」が行えます。

もっとも、あまり使用機会はありません。事実、組み込みでもほとんど使っていません。
php/js であまりにも処理が異なる場合に使います。組み込みで言うと `Unique` が良い例でしょう（Unique は階層が絡むので共通コード化はほぼ不可能）。

#### prevalidate メソッド

`validate` メソッドに先立って行われる php だけの処理です。
上記の `getJavascriptCode` を使えば js の事前処理は行なえますが、php の事前処理は行なえません。
php で事前処理が行いたい場合に使用します。

こちらもあまり使用機会はなく、組み込みでもほとんど使っていません。
更にシンタックスさえ同じであれば下記の `validate` メソッドの中で `$context['lang']` で分岐して記述することも可能です。
Unique で使用しているので参考にしてください。

#### validate メソッド

検証処理の本体を記述します。

各引数の詳細などはある程度後述しますが、原則として組み込みクラスを参照することで補完してください。
とりあえず重要な性質は**このメソッドのコードは js としても実行される**という点です。

php と js の言語としてのシンタックスはかなり似ているため、ちょっとした工夫を施せば割りとそのまま実行可能です。
そのちょっとした工夫をライブラリで吸収することでほぼ php コードで書くだけで js として実行できるようにしています。
具体的には書きませんが下記のようなものです。

- 変数宣言は不要
    - php レイヤーで巻き上げて var 宣言します
- php の多様な関数が使える
    - 実装は locutus にほぼ依存しています
- 一部の非互換シンタックスを関数化
    - 故に function use や foreach, キャストなどは関数を呼ぶ必要があります

validate には下記の引数が渡ってきます。

- `$value`: 検証する値自身です
- `$fields`: 値自身 とは別の、 getFields で得られた別の値です。ややこしいことをしない限りは空配列です
- `$params`: 自身のアンダースコアから始まるプロパティ配列です
- `$consts`: 自身のクラス定数配列です
- `$error`: エラー通知クロージャです。エラーの通知にはこのクロージャを使用します
- `$context`: シンタックスや実行言語、インスタンスなどが詰まった実行コンテキスト配列です

要するに php レイヤーでは self や $this で参照できるものが js では参照できないため、引数として渡ってくるものがほとんどです。
ただし `$error` だけはかなり特殊で、php 側ではメッセージキーかメッセージを渡すだけの動作ですが js ではそれに加えて下記の動作があります。

- `$error(string)`: 単一のエラーメッセージを追加します。これが基本です
- `$error(Object)`: 複数のエラーメッセージを追加します。あまり使うことはありません
- `$error(Promise)`: Promise 解決後のメッセージを追加します
    - 例えば Ajax してエラーメッセージを得る場合などに必要になります。 Promise 内では `$error(string)` がそのまま使えます
- `$error(null)`: エラーメッセージを削除します
- `$error(undefined)`: エラーに関して何もしません（追加も削除もしない）

これは「php はメッセージが得られればそれでいい」のに対して「js は表示を行ったり非同期したりする必要がある」というレイヤーの違いによるものです。
とはいえ単純な condition であれば `$error(文字列)` しか使わないはずです。
この辺は組み込みの `Ajax`, `ImageSize` が参考になるかもしれません。

#### addCustomValidation

サーバーサイドでの検証が不要なら `form.chmonos` に `addCustomValidation` メソッドが生えているので、それを使用すると js による事前事後の検証が可能です。

```js
// 事前検証
document.getElementById('form-id').chmonos.addCustomValidation(function(promises) {
    // do something
}, 'before');
// 事後検証
document.getElementById('form-id').chmonos.addCustomValidation(function(promises) {
    // do something
}, 'after');
```

それぞれ検証処理の事前・事後に登録した function がコールされます。
コールバック内の this は form 要素そのものを表します。
唯一の引数として Promise 配列が渡ってくるので、この配列に Promise を追加すると遅延実行されて処理されます。
（通常は Promise を使わず、直接エラーメッセージを表示するなどで十分でしょう。 Promise は非同期処理や Ajax など用です）。

before/after はタイミングの違いであり、シグネチャなどに違いはありませんが、共通仕様として「false を返すとそこで中断される」というのがあります（preventDefault みたいなものです）。
before で false を返すと、他の before イベントやその後の通常検証処理は実行されません。もちろん after も実行されません。
after で false を返すと、他の after イベントは実行されません。
いずれにせよ false を返すと検証結果としては false となります。

before は例えば特殊な UI の値化、完全なる前提条件の検証などに使用できます。
after は例えばファイル要素や非同期通信による一意チェックなどに使用できます。

なお、js だけのチェックとなるので重要な検証には使用しないでください。例えば「どうせ外部キーが守ってくれるので画面で親切さを出したい」のようなあくまで利便性だけに留めるべきです。

## Note

- アンドキュメントな仕様が結構多いです
- イベント順のしがらみで Form オブジェクトでレンダリングされた form は submit ボタンの name, value, formaction が反映されないことがあります
    - 非同期のツラミです。いっそのこと自分でハンドリングしたい場合は alternativeSubmit を false にしてください
- `composer build` すると npm ディレクトリが作成されますが基本的に気にしなくていいです
    - バベったり uglifyJs する予定があるので npm を利用していますが、現在は locutus のダウンロードにしか使っていません  
      その locutus も js 群はリポジトリに含めているので今のところ用途はありません

## License

MIT
