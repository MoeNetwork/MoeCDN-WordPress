# MoeCDN-WordPress
MoeNet Public libraries CDN plugin for WordPress.

由于众所周知的原因，以下服务在中国无法访问。这个插件提供的代理加速方案如下：

### Gravatar 

*.gravatar.com -> gravatar.moefont.com

### GoogleAPIs

fonts.googleapis.com -> cdn.moefont.com/fonts

ajax.googleapis.com -> cdn.moefont.com/ajax

### WordPress Emoji

s.w.org -> cdn.moefont.com/worg

### Jetpack 等 WordPress.com 提供的服务

*.wp.com -> cdn.moefont.com/wpcom

### 信息收集说明

本插件会收集您的 WordPress 站点网址、名称和版本信息，除此之外不会收集其他任何信息。所收集的信息仅用于统计目的。

信息收集相关的代码位于 MoeCDN::collect() ，您可以清楚地了解到它的行为。

如果您不希望发送信息，请在插件设置页面取消激活这个选项。注意，在您第一次保存设置之前，虽然相关选项是默认勾选的，但是您的信息此时是尚未被收集的。
