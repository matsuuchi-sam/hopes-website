# GTM + GA4 セットアップガイド（shihousyoshi-hopes.jp）

司法書士法人ホープスのWebサイトにアクセス解析を導入するための手順書。
GTM（Googleタグマネージャー）経由でGA4（Googleアナリティクス4）を設定する。

---

## 目次

1. [全体像を理解する](#1-全体像を理解する)
2. [GA4プロパティを作成する](#2-ga4プロパティを作成する)
3. [GTMコンテナを作成する](#3-gtmコンテナを作成する)
4. [HTMLにGTMタグを埋め込む](#4-htmlにgtmタグを埋め込む)
5. [GTM管理画面でGA4と接続する](#5-gtm管理画面でga4と接続する)
6. [動作確認](#6-動作確認)
7. [カスタムイベントの追加](#7-カスタムイベントの追加)
8. [GTMでカスタムイベントを設定する](#8-gtmでカスタムイベントを設定する)

---

## 1. 全体像を理解する

### なぜGTMを使うのか？

GA4のコードをHTMLに直接書くこともできるが、GTMを挟むメリット：
- **新しい計測を追加するときHTMLを触らなくていい**（GTM管理画面だけで完結）
- **計測の ON/OFF が管理画面でできる**（コード変更不要）
- 複数サイトをまとめて管理できる

### 仕組み（ざっくり）

```
ユーザーがサイトにアクセス
  ↓
HTMLに埋め込んだGTMタグが読み込まれる
  ↓
GTMが「どのデータをGA4に送るか」を判断
  ↓
GA4にデータが届く → レポートで見れる
```

### 今回追跡するもの

| 何を | なぜ |
|------|------|
| ページビュー（自動） | どのページがよく見られているか |
| お問い合わせフォーム送信 | コンバージョン（一番大事） |
| CTAボタンクリック | どこから問い合わせに来ているか |
| 電話タップ | モバイルからの問い合わせ数 |

> ページビュー・スクロール・デバイス情報などはGA4が**自動で計測**するので設定不要。

---

## 2. GA4プロパティを作成する

GA4 = アクセスデータを見るためのツール。まずこちらから作る（測定IDをGTMで使うため）。

### 手順

1. https://analytics.google.com/ にGoogleアカウントでログイン
2. 左下の **歯車アイコン（管理）** をクリック
3. 「**+ プロパティを作成**」をクリック

4. 以下を入力：
   - **プロパティ名**: `司法書士法人ホープス`
   - **レポートのタイムゾーン**: `日本`
   - **通貨**: `日本円（JPY）`
   - 「次へ」

5. ビジネスの詳細：
   - **業種**: `法律`（または近いもの）
   - **ビジネスの規模**: `小規模`
   - 「次へ」

6. ビジネス目標：
   - 「**見込み顧客の発掘**」にチェック
   - 「作成」

7. データストリームの設定：
   - 「**ウェブ**」を選択
   - **ウェブサイトのURL**: `www.shihousyoshi-hopes.jp`
   - **ストリーム名**: `ホープス公式サイト`
   - 「**ストリームを作成**」

8. 画面に表示される **測定ID**（`G-XXXXXXXXXX` の形式）をメモする

> **ここで取得したID → 後でこのファイルの「管理ID」セクションに記入**

---

## 3. GTMコンテナを作成する

GTM = サイトに埋め込む計測コードを管理するツール。

### 手順

1. https://tagmanager.google.com/ に同じGoogleアカウントでログイン

2. **初めて使う場合**:
   - 「アカウントを作成」をクリック
   - **アカウント名**: `HOPES`（会社単位の名前）
   - **国**: `日本`
   
   **既にアカウントがある場合**（HOPES CONSULTINGで作成済みなら）:
   - 右上の三点メニュー → 「コンテナを作成」

3. コンテナの設定：
   - **コンテナ名**: `shihousyoshi-hopes.jp`
   - **ターゲットプラットフォーム**: `ウェブ`
   - 「作成」→ 利用規約に「はい」

4. 画面に表示される **コンテナID**（`GTM-XXXXXXX` の形式）をメモする
   - 同時にコードスニペットも表示されるが、後で使うのでいったんOKで閉じてよい

---

## 管理ID（作成後に記入）

| 項目 | ID |
|------|-----|
| GA4 測定ID | `G-Q3KBPSYL17` |
| GTM コンテナID | `GTM-TV6RBT6D` |

> ↑ ID取得後にここを埋める。以降の手順で `GTM-XXXXXXX` `G-XXXXXXXXXX` と書いてある部分をこのIDに読み替える。

---

## 4. HTMLにGTMタグを埋め込む

### 対象ファイル

```
public/index.html
public/contact.html
public/privacy.html
public/confirm.php
public/thanks.php
```

### 埋め込むコード（2箇所）

#### (A) `<head>` 内の `<meta charset="UTF-8">` の直後に追加：

```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
<!-- End Google Tag Manager -->
```

#### (B) `<body>` 開始タグの直後に追加：

```html
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
```

> `GTM-XXXXXXX` は上でメモした実際のコンテナIDに置き換える。

### Claudeへの指示例

```
GTMコンテナID: GTM-XXXXXXX
上の5ファイル全部にGTMスニペットを埋め込んでください。
```

---

## 5. GTM管理画面でGA4と接続する

GTMにGA4の測定IDを登録して、ページビューが計測されるようにする。

### 手順

1. https://tagmanager.google.com/ を開く
2. コンテナ `shihousyoshi-hopes.jp` を選択

3. 左メニュー「**タグ**」→「**新規**」
4. 左上の名前欄に `Google タグ - GA4` と入力
5. 「**タグの設定**」の大きなエリアをクリック
6. 一覧から「**Google タグ**」を選択
7. **タグID** に GA4の測定ID（`G-XXXXXXXXXX`）を入力
8. 下の「**トリガー**」エリアをクリック → 「**All Pages**」を選択
9. 右上の「**保存**」

10. 右上の「**送信**」をクリック
11. **バージョン名**: `初期設定 - GA4接続`
12. 「**公開**」をクリック

> これだけでページビュー・ユーザー数・デバイス情報などの基本計測が始まる。

---

## 6. 動作確認

### GA4リアルタイムレポートで確認

1. https://analytics.google.com/ を開く
2. 左メニュー「**レポート**」→「**リアルタイム**」
3. 別タブで https://www.shihousyoshi-hopes.jp/ を開く
4. リアルタイム画面に「過去30分間のユーザー: 1」と表示されれば成功

### GTMプレビューモードで確認（より詳しく）

1. GTM管理画面で右上の「**プレビュー**」をクリック
2. URLに `https://www.shihousyoshi-hopes.jp/` を入力 → 「Connect」
3. サイトが別タブで開く
4. Tag Assistant画面で「Tags Fired」に `Google タグ - GA4` があれば成功

---

## 7. カスタムイベントの追加

ページビュー以外に計測したい3つのアクションをHTMLに追加する。

### イベント一覧

| イベント名 | 発火タイミング | パラメータ |
|-----------|--------------|-----------|
| `contact_form_submit` | お問い合わせフォーム送信時 | — |
| `cta_click` | CTAボタンクリック時 | cta_text, cta_location |
| `phone_click` | 電話番号リンククリック時 | — |

### Claudeへの指示例

```
以下のカスタムイベントをサイトに追加してください：
1. contact.htmlのフォーム送信時に contact_form_submit イベント
2. index.htmlのCTAボタン（お問い合わせ・電話）クリック時に cta_click / phone_click イベント
```

### コードの概要

#### contact.html — フォーム送信時

フォームのsubmitイベントで発火：
```javascript
dataLayer.push({
  'event': 'contact_form_submit'
});
```

#### index.html — CTAボタンクリック時

「お問い合わせフォーム」ボタンクリックで発火：
```javascript
dataLayer.push({
  'event': 'cta_click',
  'cta_text': 'お問い合わせフォーム',
  'cta_location': 'hero'
});
```

#### index.html — 電話番号クリック時

電話リンククリックで発火：
```javascript
dataLayer.push({
  'event': 'phone_click'
});
```

---

## 8. GTMでカスタムイベントを設定する

HTMLにコードを追加しただけでは GA4に届かない。GTM管理画面で「受け取って→GA4に送る」設定が必要。

### 作る順番: 変数 → トリガー → タグ

> 「変数」= データを入れる箱
> 「トリガー」= いつ発火するか
> 「タグ」= GA4に何を送るか

---

### 8-A. 変数を作る

GTM管理画面 →「**変数**」→ 下の「ユーザー定義変数」→「**新規**」

今回作るのは2つだけ：

| 変数名 | データレイヤーの変数名 |
|--------|----------------------|
| `DLV - cta_text` | `cta_text` |
| `DLV - cta_location` | `cta_location` |

**各変数の作り方：**
1. 「新規」をクリック
2. 左上に名前を入力（例: `DLV - cta_text`）
3. 「変数の設定」エリアをクリック
4. 「**データレイヤーの変数**」を選択
5. 「データレイヤーの変数名」に入力（例: `cta_text`）
6. 「保存」

> `contact_form_submit` と `phone_click` にはパラメータがないので変数不要。

---

### 8-B. トリガーを作る

GTM管理画面 →「**トリガー**」→「**新規**」

| トリガー名 | イベント名 |
|-----------|-----------|
| `CE - contact_form_submit` | `contact_form_submit` |
| `CE - cta_click` | `cta_click` |
| `CE - phone_click` | `phone_click` |

**各トリガーの作り方：**
1. 「新規」をクリック
2. 左上に名前を入力（例: `CE - contact_form_submit`）
3. 「トリガーの設定」エリアをクリック
4. 「**カスタム イベント**」を選択
5. 「イベント名」に入力（例: `contact_form_submit`）
6. 「保存」

> **重要:** イベント名はHTMLのコードと**完全一致**させること。

---

### 8-C. タグを作る

GTM管理画面 →「**タグ**」→「**新規**」

#### タグ1: GA4 - お問い合わせ送信

1. 名前: `GA4 - お問い合わせ送信`
2. 「タグの設定」→「**Google アナリティクス: GA4 イベント**」
3. **測定ID**: `G-XXXXXXXXXX`（自分のIDに置き換え）
4. **イベント名**: `contact_form_submit`
5. パラメータ: なし
6. 「トリガー」→ `CE - contact_form_submit` を選択
7. 「保存」

#### タグ2: GA4 - CTAクリック

1. 名前: `GA4 - CTAクリック`
2. 「タグの設定」→「**Google アナリティクス: GA4 イベント**」
3. **測定ID**: `G-XXXXXXXXXX`
4. **イベント名**: `cta_click`
5. 「イベント パラメータ」→「行を追加」×2回:
   - パラメータ名: `cta_text` → 値: `{{DLV - cta_text}}`
   - パラメータ名: `cta_location` → 値: `{{DLV - cta_location}}`
6. 「トリガー」→ `CE - cta_click` を選択
7. 「保存」

#### タグ3: GA4 - 電話クリック

1. 名前: `GA4 - 電話クリック`
2. 「タグの設定」→「**Google アナリティクス: GA4 イベント**」
3. **測定ID**: `G-XXXXXXXXXX`
4. **イベント名**: `phone_click`
5. パラメータ: なし
6. 「トリガー」→ `CE - phone_click` を選択
7. 「保存」

---

### 8-D. 公開する

設定を作っただけではサイトに反映されない。**必ず公開が必要。**

1. 右上の「**送信**」をクリック
2. **バージョン名**: `カスタムイベント追加`
3. 「**公開**」をクリック

> 公開しても設定は消えない。バージョン管理されているのでいつでも前の状態に戻せる。

---

## 進め方チェックリスト

- [ ] GA4プロパティを作成 → 測定IDをメモ（[手順2](#2-ga4プロパティを作成する)）
- [ ] GTMコンテナを作成 → コンテナIDをメモ（[手順3](#3-gtmコンテナを作成する)）
- [ ] このファイルの「管理ID」セクションにIDを記入
- [ ] HTMLにGTMスニペットを埋め込む（[手順4](#4-htmlにgtmタグを埋め込む) → Claudeに依頼）
- [ ] GTMでGA4接続（Googleタグ設定）（[手順5](#5-gtm管理画面でga4と接続する)）
- [ ] 動作確認（[手順6](#6-動作確認)）
- [ ] カスタムイベントのコード追加（[手順7](#7-カスタムイベントの追加) → Claudeに依頼）
- [ ] GTMでカスタムイベント設定（[手順8](#8-gtmでカスタムイベントを設定する)）
- [ ] 最終動作確認

---

## クイックリファレンス

| 項目 | URL |
|------|-----|
| GTM管理画面 | https://tagmanager.google.com/ |
| GA4管理画面 | https://analytics.google.com/ |

| サイト | GTM コンテナID | GA4 測定ID |
|--------|---------------|-----------|
| shihousyoshi-hopes.jp | `GTM-TV6RBT6D` | `G-Q3KBPSYL17` |
| hopesconsul.com（参考） | GTM-W278K6L7 | G-E06ETKRRN8 |
