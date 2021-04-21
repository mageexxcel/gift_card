# Excellence_GiftCard 
## Magento 2 Extension

### GiftCard extension allow you to purchase giftcards and redeem as store credit.

With Excellence GiftCard Extension, send gift card to your love one with custom message and scheduled date via email.


## Features:

1. Store owner can enable/disable this module from store configuration.
2. After installation and enabling this module admin can add add product of giftcard type.
3. User on store front can purchase this product by filling required details on product detail page.
4. After successfull purchasing of giftcard magento cron send the giftcard details to the mentioned recipent on scheduled date automatically via mail.
5. After receiving giftcard receipent can login into their account and can redeem the gift card sent via mail.
6. Each gift card can redeemed only once and only by one user.
7. On successfull redeemption of giftcard user get equivalent store credit value into their wallet.
8. Excellence GiftCard module reuires Excellence StoreCrdit module for proper functioning.
___________________________________________________________________________________________________
## How Store Credit Works
:memo: Dependency of module
<div>
	<div>:clock1: Excellence_GiftCard module depends on Excellence_StoreCredit module </div>
</div>
__________________________________________________________________________________________________
## Steps For Activation
<div>
	<div>
	   <b>Path:</b>Login To Your Admin>Store>Configuration>Excellence>GiftCard.
	</div>
	<div>
		<b>Description: </b> <div>:pencil2: Login to Your admin Account</div>
				     <div>:pencil2: Click on Store option on left menu bar at bottom</div>
				     <div>:pencil2: Then Select Configuration Text</div>
				     <div>:pencil2: A configuration page will open, then click on Excellence from left grid</div>
				     <div>:pencil2: Then Click on Enable GiftCard module from drop down list</div>
				     <div>:pencil2: Fill the desire fields then click on save button</div>
		<div>:pencil2: Now the GiftCard has been activated on your store.</div>
	</div>
</div>
******************************************************************************************************

_______________________________________________________________________________________________________

******************************************************************************************************
## Steps For Redeemption GiftCard

User need to login into his/her account on storefront, under his/her account section user can find a tab on left labeling GiftCard. Under GiftCard tab user can enter the giftcard received via email. After successfull validation of card the store crdeit amout against giftcard added to user's account. And the transaction history can be found under storecredit tab.
user can do the same from checkout page.


___________________________________________________________________________________________________
## Screenshots

### Addgiftcardproduct
<a href="https://ibb.co/mbCrs13"><img src="https://i.ibb.co/hLRx5hS/screenshot-4.png" alt="screenshot-4" border="0"></a>
### Productviewpage
<a href="https://ibb.co/0VwXsqC"><img src="https://i.ibb.co/Qj5cD9P/screenshot-3.png" alt="screenshot-3" border="0"></a>
### Storefront
<a href="https://ibb.co/nCxQCk8"><img src="https://i.ibb.co/BN0TNsK/screenshot-7.png" alt="screenshot-7" border="0"></a><br />


## Prerequisites

### Use the following table to verify you have the correct prerequisites to install this Extension.
<table>
	<tbody>
		<tr>
			<th>Prerequisite</th>
			<th>How to check</th>
			<th>For more information</th>
		</tr>
	<tr>
		<td>Apache 2.2 or 2.4</td>
		<td>Ubuntu: <code>apache2 -v</code><br>
		CentOS: <code>httpd -v</code></td>
		<td><a href="https://devdocs.magento.com/guides/v2.2/install-gde/prereq/apache.html">Apache</a></td>
	</tr>
	<tr>
		<td>PHP 5.6.x, 7.0.2, 7.0.4 or 7.0.6</td>
		<td><code>php -v</code></td>
		<td><a href="http://devdocs.magento.com/guides/v2.2/install-gde/prereq/php-ubuntu.html">PHP Ubuntu</a><br><a href="http://devdocs.magento.com/guides/v2.2/install-gde/prereq/php-centos.html">PHP CentOS</a></td>
	</tr>
	<tr><td>MySQL 5.6.x</td>
	<td><code>mysql -u [root user name] -p</code></td>
	<td><a href="http://devdocs.magento.com/guides/v2.2/install-gde/prereq/mysql.html">MySQL</a></td>
	</tr>
</tbody>
</table>


