### 1. Introduction:

* Bagisto seller Extension allow Admin to add virtual seller.

* Virtual Seller Can be created.

* Customer can compare diffrent sellers product.

* Admin can assign product to seller.

* Customer can choose the different seller before buying the product.

### 2. Requirements:

* **Bagisto**: v0.1.6 or higher.


### 3. Installation:

* Unzip the respective extension zip and then merge "packages" folder into project root directory.

* Goto config/app.php file and add following line under 'providers'

~~~
Webkul\Seller\Providers\SellerServiceProvider::class
~~~

* Goto composer.json file and add following line under 'psr-4'

~~~
"Webkul\\Seller\\": "packages/Webkul/Seller"
~~~

* Run these commands below to complete the setup

~~~
composer dump-autoload
~~~

~~~
php artisan migrate
~~~

~~~
php artisan route:cache
~~~

~~~
php artisan db:seed --class=Webkul\\Seller\\Database\\Seeders\\DatabaseSeeder

If your are windows user then run the below command-

php artisan db:seed --class="Webkul\Seller\Database\Seeders\DatabaseSeeder"
~~~

~~~
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
~~~


> That's it, now just execute the project on your specified domain.
