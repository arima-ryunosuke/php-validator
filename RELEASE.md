# RELEASE

バージョニングはセマンティックバージョニングでは**ありません**。

| バージョン   | 説明
|:--           |:--
| メジャー     | 大規模な仕様変更の際にアップします（クラス構造・メソッド体系などの根本的な変更）。<br>メジャーバージョンアップ対応は多大なコストを伴います。
| マイナー     | 小規模な仕様変更の際にアップします（中機能追加・メソッドの追加など）。<br>マイナーバージョンアップ対応は1日程度の修正で終わるようにします。
| パッチ       | バグフィックス・小機能追加の際にアップします（基本的には互換性を維持するバグフィックス）。<br>パッチバージョンアップは特殊なことをしてない限り何も行う必要はありません。

なお、下記の一覧のプレフィックスは下記のような意味合いです。

- change: 仕様変更
- feature: 新機能
- fixbug: バグ修正
- refactor: 内部動作の変更
- `*` 付きは互換性破壊

## x.y.z

- locutus のハンドリングを webpack に投げたい
    - 利用側で必要関数のみ出力したいのでそんな簡単な話じゃなかった・・・
- 色々と辛すぎるのでバベりたい
- rails みたいに name を entity[field] にしてみたいが・・・
- var の撲滅（var/let/const が混在していて辛い）

## 2.2.7

- [feature] Callback の生関数対応
- [refactor] php/js の変数の正規化
- [feature] RegexAll を追加
- [feature] context に object キャストを追加
- [feature] 同じタイプのメッセージを蓄積する機能

## 2.2.6

- [feature] dump した時の情報量が大きすぎるのを削減

## 2.2.5

- [feature] Ajax に INVALID を設けて bool 値に対応する
- [feature] エラー値が bool の場合に特別扱いする

## 2.2.4

- [feature] option のテキスト を label と textnode に出す機能
- [feature] バリデーションのタイミングを button.click に変更可能にした
- [fixbug] submitting でキャンセルしたのに submitted が呼ばれる不具合
- Revert "[fixbug] preventDefault が効いていない"
- Merge tag 'v1.3.8'

## 2.2.3

- [feature] vuejs で使うデータを外出し
- [change] 必須マーク付与の disabled は自身だけを見る
- [refactor] submit 分岐を requestSubmit で統一
- [fixbug] preventDefault が効いていない

## 2.2.2

- [refactor] code format
- [feature] vnode の機能改善
- [feature] value の取得機能
- [feature] propagate で伝播条件を指定できる機能
- [feature] DataUri に getAccepts を追加
- [feature] DataUri の名前付き引数と type のワイルドカード
- [feature] Compare に包含演算子を追加
- [refactor] Requires の命名規則がメチャクチャだったので修正
- [refactor] インラインスタイルを css に逃がす
- Merge tag 'v1.3.7'
- Merge tag 'v1.3.6'

## 2.2.1

- [docker] コンテナ名が古かったので修正
- Merge tag 'v1.3.5'

## 2.2.0

- [*change] DOMContentLoaded を type=module に変更
- [*change] async/await/Promise の気持ち悪いところを修正
- [feature] data 属性に parent, name を追加
- [fixbug] normalize は初期化時に null で呼ばれることもある

## 2.1.1

- [merge] v1.3.3
- [merge] v1.3.2

## 2.1.0

- [merge] v1.3.1

## 2.0.3

- [feature] php8.2 のエラーを修正

## 2.0.2

- [composer] update

## 2.0.1

- [merge] 1.2.10

## 2.0.0

- [change] php>=8.0
- [*change] getAllInput の返り値を Generator に変更
- [*change] delimiter オプションを実装
  - 実質的な非互換は js の submit で e.stopPropagation() しなくなったのみ
- [feature] invalids 属性の追加
- [*fixbug] DataUri の convertible が逆だったので修正
- [*change] Condition の生成バリエーションを削除
  - ネイティブの名前付き引数で十分
- [*change] 原則としてすべて自動エスケープとする
  - 実質的には title がエスケープされるようになった
- [*change] 動的メッセージを実装
  - メッセージテンプレートの記法が変わっている
- [*change] StringLength にあった書記素単位を StringWidth に再実装
- [*change] datalist の専用属性化
  - これまでは options を指定すると datalist として扱われていたが、明示的に datalist として指定するようになった
- [*change] レガシーな仕様を削除
  - suboption/subposition を削除
    - invalid-option-prefix で代替可能
  - 属性口出し系の削除
    - ime-mode: もはや息をしていない
    - maxlength: 検証がちゃんと走るなら邪魔なことが多い
    - いずれにせよ設定したいなら html で明示的に与えればよい（あくまで自動設定の削除である）
- [*change] 後方互換用のコードを削除
  - 配列判定が厳密になった
    - 配列を飛ばすためには明示的な multiple:true が必須
  - combobox の廃止
  - StringLength の grapheme を廃止
    - 少し実装を変えて StringWidth に移譲
  - FileType の * による mimetype 不明判定を廃止
  - Digits の sign:null を廃止
    - 明示的な指定が必要（とはいえデフォルト '+-' なので実質的には影響なし）
  - AlphaDigit のアンダースコア特別扱いを廃止

## 1.3.8

- [fixbug] base64_decode が正しく動いていない

## 1.3.7

[fixbug] data-vtemplate-name が script と被っており DOM が生成できない

## 1.3.6

[fixbug] index が重複する不具合

## 1.3.5

- [refactor] パフォーマンスチューニング
- [feature] js コメントと不要コードの除去

## 1.3.4

- [feature] combobox 判定が暴走していたので使用フラグを用意

## 1.3.3

- [feature] normalize フック

## 1.3.2

- [feature] エラートースト数を制限する機能
- [fixbug] node を返していない不具合

## 1.3.1

- [refactor] 無駄なコードがあったので削除

## 1.3.0

- [*change] index なしで disabled になる仕様を廃止
- [*change] エラー表示は各フォームに紐づくようにする
- [fixbug] inline が center になっていない不具合
- [feature] ユーティリティ的なメソッドを追加
- [feature] vnode のキャッシュと this 化
- [feature] バリデーションする inputs を直接指定できる機能
- [fixbug] spawn イベントに未確定の値が渡ってくる不具合
- [fixbug] 様々なしがらみで checkbox は bool で来ることがある

## 1.2.11

- [composer] update

## 1.2.10

- [feature] form option にデフォルト属性を持たせる機能
- [feature] 入力内容を html 化する機能
- [fixbug] filemanage がコールバックを受け付けない不具合

## 1.2.9

- [refactor] npm の更新とメンテ
- [feature] ファイルドロップイベントを組み込みで用意
- [feature] needless オプションを新設

## 1.2.8

- [feature] AlphaDigit で任意の記号を許可する機能

## 1.2.7

- [feature] Digits で桁数未満も許す設定
- [fixbug] Digits で +- を与えると1桁多く入力できる不具合を修正

## 1.2.6

- [feature] error.js の改善

## 1.2.5

- [fixbug] vuejs の警告対応
- [fixbug] エラー通知の不具合を修正
- [feature] js 側の改善
- [refactor] js 生成処理を変更
- [feature] inputs 検証の開始・終了イベントを追加
- [feature] AlphaDigit Condition を追加
- [feature] Distinct Condition を追加
- [feature] getFixture を実装
- [feature] Step の時刻対応
- [feature] Digits に符号と桁数のチェックを実装
- [feature] UniqueChild の空文字重複対応
- [feature] FileName の Windows フルパス対応
- [refactor] promise のための特別扱いを廃止

## 1.2.4

- [feature] maxlength の有効モードを追加
- [feature] 一部の Condition で複数値をサポート
- [feature] validate の記述内のアロー関数に対応

## 1.2.3

- [feature] datalist の生成機能
- [feature] ArrayExclusion を追加
- [feature] StringLength の書記素単位対応

## 1.2.2

- [feature] 全てが完了して submit する前後のイベントを追加

## 1.2.1

- [feature] Number Condition 追加
- [feature] エラー表示の分離を改善
- [fixbug] 無修飾な .validatable に :input を付与

## 1.2.0

- [change] Ajax のリクエスト削減機構を是正
- [*change] 検証フェーズと通知フェーズを明確に分ける
- [feature] mimetype の強化と trait での一本化
- [fixbug] norequire が必須エラー以外にも効いていた不具合を修正
- [fixbug] 中途半端な入力で validation が走らない不具合を修正
- [fixbug] Revert "[feature] 「DOM消え」の定義に不可視要素を追加"
- [fixbug] datetime-local の秒問題を修正
- [feature] Date に InferableType を implement
- [feature] Date に Range を implement
- [feature] Date に member パラメータを追加

## 1.1.4

- [feature] validate 引数に selector を追加
- [feature] 「DOM消え」の定義に不可視要素を追加
- [feature] Input の setter を実装
- [feature] 再帰的に input を返す getAllInput を実装
- [refactor] 使用しない場合はディスクアクセスとクラスロードを抑制

## 1.1.3

- [feature] エラー処理の改善
- [refactor] array 専用 Condition を汎化
- [feature] array の中の重複項目があるか検査する UniqueChild を追加
- [feature] array の中の必要項目があるか検査する RequiresChild を追加
- [fixbug] vuejs で array の dummy input を生成するとエラーになる不具合を修正
- [fixbug] array に initialize な condition を与えると notice が出る不具合を修正
- [feature] 全メッセージを変更する機能
- [feature] ルール配列に null が来た時にスルーする機能
- [feature] vnode にタグ関数を指定できる機能

## 1.1.2

- [fixbug] invalid-option-prefix 由来の無効値の場合に validation_invalid が付与されない不具合を修正
- [feature] required のラベリングにエラーレベル属性を付与

## 1.1.1

- [feature] options に stdClass を使用できる機能
- [feature] null でもデフォルト値が使われるようになる nullable オプションを追加
- [feature] context のルール配列にクロージャを指定できる機能

## 1.1.0

- [feature] type をルール配列で指定可能にする機能
- [*change] bump version

## 1.0.26

- [fixbug] Ajax が特定条件で動かなかった不具合を修正
- [feature] autocond にクロージャを渡すとその Condition の設定をできる機能
- [feature] エラー発火に phantom も含める

## 1.0.25

- [change] エラーの見た目を調整
- [fixbug] spawn に values を与えないとエラーになってしまう不具合を修正
- [fixbug] DataUri の正規表現が誤っていたので修正

## 1.0.24

- [feature] spawn に簡易テンプレート機能を実装
- [fixbug] autocond に配列指定時の未定義動作を修正

## 1.0.23

- [feature] DataUri Condition を追加
- [fixbug] ファイルが大きいと params でエラーが出ていた不具合を修正
- [fixbug] 検証処理で例外が飛ぶと以後が打ち切られてしまう不具合を修正
- [fixbug] エラーメッセージが最初の物から変わらない不具合を修正

## 1.0.22

- [feature] _setAutoNotInArray を実装
- [feature] warning の仕様を正式な仕様に格上げ
- [feature] stringable ならなんでも value に使えるようにした

## 1.0.21

- [refactor] input の取得と属性の正規化を共通化
- [feature] label のクロージャ対応
- [feature] radio/checkbox の label の中に納める機能

## 1.0.20

- [feature] submitEvent.submitter が完備されたので使用する
- [feature] js 系の値戻しが弱かったので強化

## 1.0.19

- [feature][template] js 側での値の取得の強化
- [feature] php 8.1 対応

## 1.0.18

- [change][script] jQuery 依存の排除と IE の切り捨て
- [change][template] 気になったところを修正
- [feature][Input] vuejs に暫定対応
- [feature][Input] input をまとめる grouper ルールを追加
- [feature][Input] Input を継承せずともデフォルトルールを上書きできる機能
- [fixbug][Input] multiple 対応 type から radio を除外
- [fixbug][Input] ファイルが飛んできていない時に notice が出る不具合を修正
- [fixbug][Condition] outputJavascript の最新判定に誤りがあり出力されないことがある不具合を修正

## 1.0.17

- [fixbug][Input] Condition 自身が setCheckMode しても Input の checkmode 指定で上書かれていた不具合を修正

## 1.0.16

- [fixbug][Input] 現在値がすべて invalid になっていた不具合を修正

## 1.0.15

- [feature][template] event の発火タイミングを指定可能にした
- [feature][Input] 現在の値を尊重する suboptions/subposition を実装
- [fixbug][Input] message 指定が setAutoCondition には効いていなかった不具合を修正

## 1.0.14

- [feature][Input] label でラベル文字列を指定できるように修正
- [fixbug][Condition/Date] 不正日時文字列を渡すと 1970-01-01 になってしまう不具合を修正

## 1.0.13

- [change][all] php 7.4 対象と php8.1 の暫定対応
- [change][script] クリック時のスクロールを組み込みに変更
- [fixbug][template] テンプレートの root 要素はスキップされる不具合を修正
- [change][Input] pseudo Hidden に目印属性を追加
- [feature][Input] combobox の実装
- [feature][Condition] ConvertibleValue インターフェースを導入
- [feature][Condition/FileName] ファイル名バリデータを追加
- [feature][Condition/StringWidth] 文字幅バリデータを追加

## 1.0.12

- [feature][Form] フィルタだけを行う filter メソッドを実装
- [feature][Context] エラーをクリアする clear メソッドを実装

## 1.0.11

- [feature][Context] 値のフィルタ処理を実装
- [feature][Input] getAjaxResponse を実装
- [fixbug][Input] pseudo 指定時に setAutoInArray でコケる不具合を修正

## 1.0.10

- [feature][Input] pseudo で値を指定できるように修正
- [feature][Condition/Hostname] ポート対応

## 1.0.9

- [change][Input] javascript を廃止して checkmode を新設
- [fixbug][Input] _getRange の不具合を修正
- [feature][Condition/Step] 実装

## 1.0.8

- [feature][template] spawn 開始/終了イベントを実装

## 1.0.7

- [fixbug][Form] script の非同期読み込み時にエラーが出てしまう不具合を修正

## 1.0.6

- [feature] bump version

## 1.0.5

- [fixbug] php 7.4 のエラーを修正

## 1.0.4

- [feature][Condition/Password] プリセットに lower/upper を追加

## 1.0.3

- [Context] isset を実装

## 1.0.2

- [bin] npm コマンドが動いていなかったので修正
- [bin] validator.js を生成する手段がなかったのでコマンドを追加
- [Form] 属性引数が css セレクタ文字列を受け入れるように修正
- [Form] 空引数の呼び出しがわかりづらすぎるので open/close エイリアスメソッドを追加
- [Condition/AbstractCondition] 出力フォーマットを変更

## 1.0.1

- [all] composer update

## 1.0.0

- 公開
