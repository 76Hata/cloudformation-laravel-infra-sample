# CloudFormation Laravel WebApp Infrastructure Example

このリポジトリは、AWS CloudFormation によって構築される Laravel 用のWeb開発インフラテンプレートです。  
セキュリティを重視し、SSH秘密鍵はCloudFormationで一切保持せず、**各自の公開鍵を渡して構築**します。

---

## 📐 構成概要

- VPC（単一AZ構成）
- Publicサブネット: Bridgeサーバ（踏み台）
- Privateサブネット: Webサーバ（Develop・Dev）、RDS（マスター・リードレプリカ）
- NAT Gateway、Route Table、セキュリティグループ自動構成
- SSH接続用の公開鍵はパラメータ入力で指定（秘密鍵は自前）

---

## 🔐 SSH鍵の生成と指定方法

### ✅ Mac / Linux の場合

```bash
ssh-keygen -t rsa -b 2048 -f DemoKeyPair
```

- `DemoKeyPair`（秘密鍵）と `DemoKeyPair.pub`（公開鍵）が作成されます。
- CloudFormation 実行時、`.pub` の内容を `SSHAuthorizedKey` パラメータに渡します。

---

### ✅ Windows（PuTTY使用）の場合

1. **PuTTYgen** を起動（未インストールの場合は PuTTY セットをダウンロード）
2. 「Generate」をクリックしてキーを生成
3. 「**Public key for pasting into OpenSSH authorized_keys file**」 の内容をコピー
4. 「Save private key」で `.ppk` ファイルとして保存（秘密鍵）
5. コピーした公開鍵を `SSHAuthorizedKey` に貼り付けて CloudFormation を実行

---

## 🚀 CloudFormation 実行方法（例: AWS CLI）

```bash
aws cloudformation create-stack   --stack-name LaravelInfraStack   --template-body file://cloudformation-laravel-webapp-infra-example.yml   --parameters ParameterKey=SSHAuthorizedKey,ParameterValue="$(cat DemoKeyPair.pub)"
```

※ Windowsユーザーは公開鍵文字列をコピペで貼り付けてください。

---

## 💻 SSH接続例

### Bridgeサーバに接続（グローバルIPで）

```bash
ssh -i DemoKeyPair.pem ec2-user@<BridgeのパブリックIP>
```

> ※ `.pem` ファイル形式で保存している場合は拡張子も指定してください  
> `.ppk` は PuTTY 用、CLI では使えません。

---

### Bridgeから内部のWebサーバへ接続

```bash
ssh -i ~/DemoKeyPair.pem ec2-user@10.0.200.xxx
```

---

## 🎯 このテンプレートで学べること

- Infrastructure as Code（IaC）の実践
- セキュアなSSH鍵管理
- プライベートサブネットとNATの使い分け
- マルチサーバ構成と踏み台アクセスの構築
- Laravel環境の自動展開への布石

---

## 📌 注意事項

- 本テンプレートは **開発・検証用の構成**です。
- 本番環境では、IAM Role、SSM Session Manager、Secrets Manager などの併用を推奨します。
- SSH秘密鍵（.pemや.ppk）は **絶対にGitHubなどに公開しないでください**。
- 本リポジトリのコードやテンプレートを利用したことによる直接的・間接的な損害について、作成者は一切の責任を負いません。利用は自己責任でお願いいたします。

---

## 🙏 Special Thanks

この構成は、セキュリティと再現性の両立を目指して設計されました。  
改善点や提案などあれば、Issue・PR大歓迎です！
