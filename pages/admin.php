<?php

  include("../php/session_start.php");
		
  if($_SESSION["roles"] !== "admin"){
    header('Location: ./mask_page.php');
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
    <button onclick="fetchAndDisplayOrders('all')">All</button>
    <button onclick="fetchAndDisplayOrders('pending')">Pending</button>
    <button onclick="fetchAndDisplayOrders('wip')">WIP</button>
    <button onclick="fetchAndDisplayOrders('complete')">Completed</button>
    <button onclick="fetchAndDisplayOrders('canceled')">Canceled</button>
    <br /><br />
    Order # or Email <input type="text" name="" id="orderOrEmail" />
    <button onclick="searchOrderOrEmail()">Search</button>
    <hr />
    <div id="orderlist"></div>

    <script>
      const orderlist = document.getElementById("orderlist");
      let lastFilter = "all";

      async function fetchAndDisplayOrders(filter) {
        orderlist.innerHTML = "";
        lastFilter = filter;
        console.log(lastFilter)

        if (filter === "all") {
          url = "fetchAll";
        } else if (filter === "pending") {
          url = "fetchPending";
        } else if (filter === "wip") {
          url = "fetchWIP";
        } else if (filter === "completed") {
          url = "fetchCompleted";
        } else if (filter === "canceled") {
          url = "fetchCanceled";
        }

        return new Promise((resolve) => {
          fetch(`../php/admin_${url}.php`, {
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
                <h3>${filter.toUpperCase()} Orders</h3>
                <hr>
                `
              );
              data.forEach(function (value, index) {
                orderlist.insertAdjacentHTML(
                  "beforeend",
                  `
                  <p>Date: ${value.Date}</p>
                  <p>${value.Email}</p>
                  <p>Order # ${value.orderNum}</p>
                  <p>Status: ${value.orderStatus} 
                    <button onclick="updateStatus([${value.orderNum}, 'WIP'])">Status - WIP</button>
                    <button onclick="updateStatus([${value.orderNum}, 'Pending'])">Status - Pending</button>
                    <button onclick="updateStatus([${value.orderNum}, 'Canceled'])">Status - Canceled</button>
                    <button onclick="updateStatus([${value.orderNum}, 'Complete'])">Status - Complete</button>
                  </p>
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
      fetchAndDisplayOrders("all");

      // orderNumSt = one array, two items [orderNum, status];
      function updateStatus(orderNumSt) {
        orderlist.innerHTML = "";
        fetch("../php/admin_updateStatus.php", {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(orderNumSt),
        })
          .then((response) => response.json())
          .then((data) => {
            // console.log("Success:", data);
            fetchAndDisplayOrders(lastFilter);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      function refreshLastFilter(lastFilter){
        if (lastFilter === "all") {
          fetchAndDisplayOrders();
        } else if (lastFilter === "pending") {
          fetchAndDisplayPending();
        } else if (lastFilter === "wip") {
          fetchAndDisplayWIP();
        } else if (lastFilter === "canceled") {
          fetchAndDisplayCanceled();
        } else if (lastFilter === "complete") {
          fetchAndDisplayComplete();
        }
      }

      function searchOrderOrEmail() {
        let query = document.getElementById("orderOrEmail").value;
        alert(`You would have searched for "${query}"`);
      }

    </script>
  </body>
</html>
