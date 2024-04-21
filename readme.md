### 2024 - Updates in Progress

I built this years ago mostly as a way to see what PHP is like, and is still my only PHP project. While the order system is not getting much real use, I knew there were a lot of learning opportunities available in cleaning up the back end.

Main Changes:

Moved from CPannel to my own server with PHP/FPM and local MariaDB

Updated to use PDO

Replaced multiple statement queries with table join

Implemented transactions when making changes across different tables that must be atomic

Fixed cases where server and or client side validation was missing or incorrect.

Compressed mask images and rescaled them (they were 3024 x 4032 before!). Added loading gif placeholder to the mask images

---

There is still more I could fix... but I got some lessons out of what I cleaned up.

---

<h1>Designs by Tabitha</h1>

<img src="https://raw.githubusercontent.com/PaulB-H/designsbytabitha/e7b6c6db1559b963a37d3fae6e2cfd7191b825b4/images/vector/header.svg" />

<h5><small>Made with:</small><br /> HTML, CSS, JS, PHP, MariaDB/PDO</h5>

<a href="https://designsbytabitha.ca" target="_blank">https://designsbytabitha.ca</a>

<h2>Description</h2>
<p>A website for a designer to showcase her work, and also manage hand-made mask orders from friends and family. There was no payment for the masks so a payment system was not required. </p>

<h2>Details</h2>
<p>The logo on the homepage was made by taking a commissioned image and running it through a vector converter, turning it into an SVG.</p>

<p>The masks page pulls data from the SQL database, and links to account management, so you can log in and place an order.</p>
<p>User passwords are hashed and salted before entry into the DB, and I used PHP-Session to manage logins.</p>

<p>I used a lightweight popup library called iziToast to help build the shopping cart functionality, and display other alerts and errors.</p>
<img src="https://raw.githubusercontent.com/PaulB-H/designsbytabitha/master/images/readme_images/izitoast.PNG" alt="example of izitoast modal library in use" />
<p><sup><strong>Example of izitoast in use</strong></sup></p>

<p>There is an administrator order-management section, providing the ability to filter and search orders, update their status, and keep track of items made so far vs how many were requested.</p>
<img src="https://raw.githubusercontent.com/PaulB-H/designsbytabitha/master/images/readme_images/admin_order_details.PNG" alt="image of admin order management system" />
<p><sup><strong>Example of admin order management system</strong></sup></p>

<h2>Libraries / Frameworks / Packages</h2>
<ul>
<li><a href="https://github.com/marcelodolza/iziToast" target="_blank">iziToast</a></li>
<li><a href="https://github.com/nolimits4web/swiper" target="_blank">Swiper</a></li>
<li><a href="https://github.com/jquery/jquery" target="_blank">jQuery</a></li>
</ul>

<h2>Tools</h2>
<ul>
<li><a href="https://cpanel.net/" target="_blank"><strike>cPanel</strike></a></li>
<li><a href="https://github.com/phpmyadmin/phpmyadmin" target="_blank"><strike>phpMyAdmin</strike></a></li>
</ul>
