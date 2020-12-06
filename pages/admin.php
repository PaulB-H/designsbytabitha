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
    <title>DBT Admin</title>
    <link rel="stylesheet" href="../styles/admin.css">
  </head>
  <body>
    <h3>Admin Page</h3>
    <button onclick="location = './session.php' ">Back</button>
    <button onclick="fetchAndDisplayOrders('all')">All</button>
    <button onclick="fetchAndDisplayOrders('pending')">Pending</button>
    <button onclick="fetchAndDisplayOrders('wip')">WIP</button>
    <button onclick="fetchAndDisplayOrders('canceled')">Canceled</button>
    <button onclick="fetchAndDisplayOrders('complete')">Complete</button>
    <br /><br />
    Order # or Email <input type="text" name="" id="orderOrEmail" />
    <button onclick="searchOrderOrEmail()">Search</button>
    <hr />
    <div id="orderlist"></div>

    <script>
      const orderlist = document.getElementById("orderlist");
      let lastFilter = "all";
      let singleOrder = false;
      let lastOrder;

      async function fetchAndDisplayOrders(filter) {
        orderlist.innerHTML = "";
        lastFilter = filter;
        singleOrder = false;
        let url;

        if (filter === "all") {
          url = "fetchAll";
        } else if (filter === "pending") {
          url = "fetchPending";
        } else if (filter === "wip") {
          url = "fetchWIP";
        } else if (filter === "complete") {
          url = "fetchComplete";
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
                  <button onclick="fetchAndDisplaySingle(${value.orderNum})">View Details</button>
                  <br><br>
                  <p>Status: <strong style="background: yellow; padding: 3px;">${value.orderStatus}</strong>
                    <br><br>
                    <button onclick="updateStatus([${value.orderNum}, 'Pending'])">Set <br> Pending</button>
                    <button onclick="updateStatus([${value.orderNum}, 'WIP'])">Set <br> WIP</button>
                    <button onclick="updateStatus([${value.orderNum}, 'Canceled'])">Set <br> Canceled</button>
                    <button onclick="updateStatus([${value.orderNum}, 'Complete'])">Set <br> Complete</button>
                  </p>
                  <button style="background: red; color: white;">Delete Order</button>
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

      function fetchAndDisplaySingle(ordernum){
        orderlist.innerHTML = "";
        singleOrder = true;
        lastOrder = ordernum;
        fetch(`../php/admin_fetchDetails.php`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(ordernum),
        })
          .then((response) => response.json())
          .then((data) => {
            orderlist.innerHTML = "";
            let details = data.shift();
            orderlist.insertAdjacentHTML(
              "afterbegin",
              `
              <h3>Order # ${details.orderNum} Details</h3>
              <h3 style="background: yellow;">Status: ${details.orderStatus}</h3>
              <h3>Date: ${details.Date}</h3>
              <h3>Email: ${details.Email}</h3>
              <hr>
              `
            );
            data.forEach(function (value, index) {
              orderlist.insertAdjacentHTML(
                "beforeend",
                `
                <p>Fabric: ${value.item}</p>
                <p>Size: ${value.size}</p>
                <p>Number Ordered: ${value.qnty}</p>
                <p>Number Made: ${value.made}</p>
                <br><br>
                <button onclick="updateStatus([${ordernum}, 'Pending'])">Set <br> Pending</button>
                <button onclick="updateStatus([${ordernum}, 'WIP'])">Set <br> WIP</button>
                <button onclick="updateStatus([${ordernum}, 'Canceled'])">Set <br> Canceled</button>
                <button onclick="updateStatus([${ordernum}, 'Complete'])">Set <br> Complete</button>
                `
              );
            });
            // resolve(data);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

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
            if(singleOrder === false){
              fetchAndDisplayOrders(lastFilter);
            } else {
              fetchAndDisplaySingle(lastOrder);
            }
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
