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
    <button onclick="fetchAndDisplayOrders()">All</button>
    <button onclick="fetchAndDisplayPending()">Pending</button>
    <button onclick="fetchAndDisplayWIP()">WIP</button>
    <button onclick="fetchAndDisplayComplete()">Completed</button>
    <button onclick="fetchAndDisplayCanceled()">Canceled</button>
    <br /><br />
    Order # or Email <input type="text" name="" id="orderOrEmail" />
    <button onclick="searchOrderOrEmail()">Search</button>
    <hr />
    <div id="orderlist"></div>

    <script>
      const orderlist = document.getElementById("orderlist");
      let lastFilter = "all";

      async function fetchAndDisplayOrders() {
        orderlist.innerHTML = "";
        return new Promise((resolve) => {
          fetch("../php/admin_fetchAll.php", {
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
                <h3>All Orders</h3>
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
              lastFilter = "all";
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }
      fetchAndDisplayOrders();

      async function fetchAndDisplayPending() {
        orderlist.innerHTML = "";
        return new Promise((resolve) => {
          fetch("../php/admin_fetchPending.php", {
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
              lastFilter = "pending";
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }

      async function fetchAndDisplayWIP() {
        orderlist.innerHTML = "";
        return new Promise((resolve) => {
          fetch("../php/admin_fetchWIP.php", {
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
                <h3>WIP Orders</h3>
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
              lastFilter = "wip";
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }

      async function fetchAndDisplayCanceled() {
        orderlist.innerHTML = "";
        return new Promise((resolve) => {
          fetch("../php/admin_fetchCanceled.php", {
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
                <h3>Canceled Orders</h3>
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
              lastFilter = "canceled";
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }

      async function fetchAndDisplayComplete() {
        orderlist.innerHTML = "";
        return new Promise((resolve) => {
          fetch("../php/admin_fetchComplete.php", {
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
                <h3>Complete Orders</h3>
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
              lastFilter = "complete";
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
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
            refreshLastFilter(lastFilter);
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
