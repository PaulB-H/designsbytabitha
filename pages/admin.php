<?php

  include("../php/session_start.php");
		
  if($_SESSION["user"] !== "Paulus" && $_SESSION["user"] !== "Tabgbernard"){
      header('Location: https://designsbytabitha.ca/');
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <h3>Admin Page</h3>
    <button onclick="location='./session.php'">Back</button>
    <button onclick="fetchAndDisplayOrders()">All</button>
    <button onclick="fetchAndDisplayPending()">Pending</button>
    <button>In Prog</button>
    <button>Completed</button>
    <button>Canceled</button>
    <br /><br />
    Order # or Email <input type="text" name="" id="orderOrEmail" />
    <button onclick="searchOrderOrEmail()">Search</button>
    <hr />
    <div id="orderlist"></div>

    <script>
      const orderlist = document.getElementById("orderlist");

      async function fetchAndDisplayOrders() {
        return new Promise((resolve) => {
          fetch("../php/admin.php", {
            method: "GET",
            headers: {
              "Content-Type": "application/json",
            },
          })
            .then((response) => response.json())
            .then((data) => {
              orderlist.innerHTML = "";
              orderlist.insertAdjacentHTML(
                "afterbegin",
                `
                <h3>All Active Orders</h3>
              `
              );
              data.forEach(function (value, index) {
                orderlist.insertAdjacentHTML(
                  "beforeend",
                  `
                  <p>Date: ${value.Date}</p>
                  <p>${value.Email}</p>
                  <p>Order # ${value.orderNum}</p>
                  <p>Status: ${value.orderStatus} <button>Update Status</button></p>
                  <button>Delete Order</button>
                  <hr>
                `
                );
              });
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }

      fetchAndDisplayOrders();

      function searchOrderOrEmail() {
        let query = document.getElementById("orderOrEmail").value;
        alert(`You would have searched for "${query}"`);
      }

      function fetchAndDisplayPending() {
        return new Promise((resolve) => {
          fetch("../php/get_pending.php", {
            method: "GET",
            headers: {
              "Content-Type": "application/json",
            },
          })
            .then((response) => response.json())
            .then((data) => {
              orderlist.innerHTML = "";
              orderlist.insertAdjacentHTML(
                "afterbegin",
                `
                <h3>Pending Orders</h3>
              `
              );
              data.forEach(function (value, index) {
                orderlist.insertAdjacentHTML(
                  "beforeend",
                  `
                  <p>Date: ${value.Date}</p>
                  <p>${value.Email}</p>
                  <p>Order # ${value.orderNum}</p>
                  <p>Status: ${value.orderStatus} <button>Update Status</button></p>
                  <button>Delete Order</button>
                  <hr>
                `
                );
              });
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }

      // let updateArr = [orderNum, status];
      let updateArr = [52, "Hello World"];

      function updateStatus(orderNumSt) {
        fetch("../php/update_status.php", {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(updateArr),
        })
          .then((response) => response.json())
          .then((data) => {
            console.log("Success:", data);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }
    </script>
  </body>
</html>
