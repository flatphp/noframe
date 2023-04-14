## 什么是SDK以及区别
SDK是复用型业务组件；
跟服务的区别：sdk是在项目本地的，以达成更细粒度的拆分和复用；   
跟本地service类的区别：SDK具有通用性和复用性，放到任何一个项目都可以接入使用；   
跟vendor的区别：vendor是基于composer引入的类库，而sdk可以放置自封装的通用类库；   

## SDK使用原则
跟vendor一样，SDK不允许业务本地编码修改；   
SDK可升级，理论上说SDK可直接覆盖替换以达到升级/降级的目的（所以如果sdk本地被修改过的话，那么让升级就变得不可行）；   

## SDK自身规则
SDK自身独立，配置注入，依赖注入；   
SDK自身对业务方是黑盒子，业务方无需关心SDK具体实现；   
SDK自身需要有暴露接口/方法的文档说明，文档随自身一起存在于git|svn里，如：readme.md；   

## SDK版本
SDK版本规则：v大版本.功能版本.fix版本，如v1.2.0，大版本可以不向下兼容，功能版本和fix版本基本是兼容的；   
SDK每一个版本自身要有一个version文件，存放当前版本信息（第一行），里面也可以编写每个版本的变更记录；   

## 项目如何使用和升级SDK
在sdk库里找到合适自己的版本，直接复制放到子目录；   
升级：从sdk库里直接复制替换；   
或者可以创建自己的composer私有库，通过composer管理；   
 

## What is the SDK and the difference
The SDK is a reusable business component;   
The difference from service: sdk is local to the project to achieve a more granular split and reuse;   
The difference from local service class: SDK is versatile and reusable, and can be used in any project;   
The difference from vendor: the vendor is more of a general-purpose class library without business feature, the SDK will have some business-oriented logic;   

## SDK usage principles
Like the vendor, the SDK not allow local modifications;   
The SDK can be upgraded. In theory, the SDK can directly override the replacement to achieve the upgrade/downgrade (so if the sdk is modified locally, then the upgrade will not be feasible);   

## SDK rules
SDK itself is independent, configuration injection, dependency injection;   
The SDK itself is a black box for the business side, and the business side does not need to care about the specific implementation of the SDK;   
The SDK itself needs to have a description of the exposed interface/method, and the documentation exists with git|svn itself, such as: readme.md;   

## SDK version
SDK version rules: v large version.function version.fix version, such as v1.2.0, large version can not be backward compatible, function version and fix version are basically compatible;   
Each version of the SDK itself must have a version file that stores the current version information (the first line), which can also write a change record for each version;   

## How to use and upgrade the SDK
Find the appropriate version in the sdk repository and copy it directly to the sdk subdirectory.
Upgrade: copy and replace directly from the sdk repository;
Or you can create your own private composer library, managed by composer;

