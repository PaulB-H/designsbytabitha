<?php

include("../php/session_start.php");

if(!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin"){
  header('Location: ./mask_page.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DBT Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../styles/admin.css">
    <style>
      button {
        margin: 5px;
        padding: 5px 0;
      }
      .invert {
        filter: invert(100%);
      }
    </style>
  </head>
  <body>
    <h3>DBT Admin</h3>

    <button onclick="location = './session.php' ">Back</button>
    <button onclick="`${document.getElementsByTagName('html')[0].classList.toggle('invert')}`">Dark</button>

    <!-- Filters -->
    <div id="filterBtns">
      <p class="tac" style="margin: auto">Filters</p>
      <button onclick="searchByStatus('fetchAll')">All</button>
      <button onclick="searchByStatus('fetchPending')">Pending</button>
      <button onclick="searchByStatus('fetchWIP')">WIP</button>
      <button onclick="searchByStatus('fetchCanceled')">Canceled</button>
      <button onclick="searchByStatus('fetchComplete')">Complete</button>
    </div>

    <hr />

    <!-- Search Form -->
    <div style="padding: 10px;">
      <form onsubmit="submitSearchForm(event)">
        <p style="margin-bottom: 5px;">Order Number or Email</p>
        <input style="padding: 5px;" type="text" name="orderOrEmail" id="orderOrEmail" value="" />
        <button type="submit">Search</button>
      </form>
    </div>

    <hr />

    <!-- Container for search results -->
    <div id="orderlist"></div>

    <div id="dialog-confirm" title="Confirm Delete Order" style="visibility: hidden;"></div>

    <script>

      /**
       * Keeps track of the last filter or search
       */
      const searchState = {
        filter: "fetchAll",
        orderNum: null,
        email: null,
      }

      /**
       * Triggered by the status filter buttons
       */
      function searchByStatus(filter) {
        const orderlist = document.getElementById("orderlist");
        orderlist.innerHTML = "";

        searchState.filter = filter;
        searchState.orderNum = null;
        searchState.email = null;

        fetch(`../php/admin_${filter}.php`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        })
          .then((response) => response.json())
          .then((data) => {
            orderlist.insertAdjacentHTML(
              "afterbegin",
              `
                <h3 style="margin: 10px 0;">
                  ${filter.toUpperCase()} Orders
                </h3>
                <hr style="margin-bottom: 10px">
              `
            );

            if (data.length <= 0) {
              orderlist.insertAdjacentHTML(
                "beforeend",
                `
                  <p style="margin-top: 10px; font-weight: bold;">No orders found</p>
                `
              );
              return;
            }

            if (data.length > 0) {
              data.forEach(function (value, index) {
                let date = new Date(value.Date);
                date = date.toDateString();

                orderlist.insertAdjacentHTML(
                  "beforeend",
                  create_order_summary(
                    value.OrderNum,
                    value.Email,
                    date,
                    value.OrderStatus,
                    true
                  )
                );
              });
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }
      searchByStatus("fetchAll");

      /**
       * Takes in order details and returns HTML summary
       * 
       * showDetails controls if the summary will display the "Show Details" button
       */
      const create_order_summary = (
        OrderNum,
        Email,
        date,
        OrderStatus,
        showDetails
      ) => {
        return `
          <br />
          <h3>Order # ${OrderNum} Details</h3>
          <br />
          <p>Customer: ${Email}</p>
          <p>Placed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${date}</p>
          <br />
          ${
            showDetails
              ? `<button onclick="fetchOrderDetails(${OrderNum})">View Details</button><br><br>`
              : ""
          }
          <h3 style="background: yellow; max-width: max-content; padding: 5px;">Status: ${OrderStatus}</h3>
          <br>
          <p style="margin-left: 75px; font-family: sans-serif">Set Status:</p>
          <div style="max-width: 300px;" class="updateStatusBtns">
            <button onclick="updateStatus([${OrderNum}, 'Pending'])">Pending</button>
            <button onclick="updateStatus([${OrderNum}, 'WIP'])">WIP</button>
            <button onclick="updateStatus([${OrderNum}, 'Canceled'])">Canceled</button>
            <button onclick="updateStatus([${OrderNum}, 'Complete'])">Complete</button>
          </div>
          <button class="deleteBtn" onclick="deleteOrder(${OrderNum})">Delete</button>
          <br><br>
          <hr>
        `;
      };

      /**
       * Takes in an array of order items
       * Returns an array of HTML elements for each item
       */
      const create_order_details = (orderData) => {
        const itemsArr = [];
        orderData.orderItems.forEach((item) => {
          const itemsMadeBg = item.Made === item.Qnty ? "green" : "red";

          const newItem = `
            <br />
            <p>Fabric: ${item.Item}</p>
            <p>Size: &nbsp;&nbsp;&nbsp;${item.Size}</p>
            <br />
            <p>Number Ordered: ${item.Qnty}</p>
            <p>Number Made:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="background-color: ${itemsMadeBg}; padding: 3px">${item.Made}</span></p>
            <br />
            <button class="updateItemsMadeBtn" onclick="updateItemsMade(
              {
                'orderNum': ${orderData.OrderNum}, 
                'fabricName': '${item.Item}',
                'size': '${item.Size}',
                'numOrdered': ${item.Qnty}, 
                'numMade': ${item.Made}, 
                'addOrSub': 'sub'
              }
            )">-1</button>
            <button class="updateItemsMadeBtn" onclick="updateItemsMade(
              {
                'orderNum': ${orderData.OrderNum}, 
                'fabricName': '${item.Item}', 
                'size': '${item.Size}',
                'numOrdered': ${item.Qnty}, 
                'numMade': ${item.Made}, 
                'addOrSub': 'add'
              }
            )">+1</button>
            
            <br><br />
            <hr>
          `;

          itemsArr.push(newItem);
        });

        return itemsArr;
      };

      /**
       * On search submission
       * We ensure a valid email or integer and call the appropriate function
       */
      function submitSearchForm(event) {
        event.preventDefault();

        const orderList = document.getElementById("orderlist");

        const form = event.target;
        const searchQuery = form.elements["orderOrEmail"].value;
        const searchQueryInt = parseInt(searchQuery);

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const validEmail = emailRegex.test(searchQuery);

        // If query is not a valid email AND not a positive integer, fail
        if (
          !validEmail &&
          (isNaN(searchQueryInt) || searchQueryInt % 1 !== 0 || searchQueryInt <= 0)
        ) {
          console.log("Invalid query, positive integer or email only");

          orderList.innerHTML = `<p style="margin-top: 15px;">Invalid query, positive integer or email only</p>`;

          return;
        }

        if (validEmail) {
          searchByEmail(searchQuery);
        } else {
          // email was not valid but searchQuery is a positive integer
          searchByOrderNum(searchQuery);
        }
      }

      function searchByEmail(email) {

        searchState.filter = null;
        searchState.orderNum = null;
        searchState.email = email;

        fetch(`../php/admin_fetchAllByEmail.php`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(email),
        })
        .then((response) => response.json())
        .then((data) => {
          orderlist.innerHTML = "";
          orderlist.insertAdjacentHTML(
            "afterbegin",
            `
            <h3 style="margin: 10px 0;">All orders for: ${email.toLowerCase()}</h3>
            <hr style="margin-bottom: 10px">
            `
          );
          if (data.length > 0) {
            data.forEach(function (value, index) {
              let date = new Date(value.Date);
              date = date.toDateString();

              orderlist.insertAdjacentHTML(
                "beforeend",
                create_order_summary(
                  value.OrderNum,
                  value.Email,
                  date,
                  value.OrderStatus,
                  true
                )
              );
            });
          } else {
            orderlist.insertAdjacentHTML(
              "beforeend",
              `
            <p style="margin-top: 10px; font-weight: bold;">No orders found</p>
            `
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
      }

      function searchByOrderNum(ordernum) {
        const orderList = document.getElementById("orderlist");

        searchState.filter = null;
        searchState.orderNum = ordernum;
        searchState.email = null;

        fetch(`../php/admin_fetchByOrderNum.php`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(ordernum),
        })
        .then((response) => response.json())
        .then((orderData) => {
          // if (orderData.error) {
          //   orderlist.innerHTML = "";

          //   orderlist.insertAdjacentHTML("beforeend",
          //     `
          //     <p style="margin-top: 10px;">${orderData.error}</p>
          //     `
          //   )
          //   return;
          // }

          orderlist.innerHTML = "";

          if (!orderData) {
            orderlist.insertAdjacentHTML(
              "beforeend",
              `
                p style="margin-top: 10px;">No Orders Found</p>
              `
            );
            return;
          }

          const date = new Date(orderData.Date).toDateString();

          // ( OrderNum, Email, date, OrderStatus, showDetails )
          const orderDetails = create_order_summary(
            orderData.OrderNum,
            orderData.Email,
            date,
            orderData.OrderStatus,
            true
          );

          orderlist.insertAdjacentHTML("afterbegin", orderDetails);
        })
        .catch((error) => {
          console.error("Error:", error);
        });
      }

      //****/
      // https://jqueryui.com/dialog/#modal-confirmation
      //****/
      function deleteOrder(num) {
        $("#dialog-confirm").dialog({
          resizable: false,
          height: "auto",
          width: "auto",
          modal: true,
          title: `Delete Order ${num} ?`,
          buttons: [
            {
              text: "YES \n Delete",
              click: function () {
                orderlist.innerHTML = "";
                $(this).dialog("close");
                fetch(`../php/admin_deleteOrder.php`, {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify(num),
                })
                .then((response) => response.json())
                .then((data) => {
                  searchByStatus("all");
                });
              },
            },
            {
              text: "NO \n Cancel",
              click: function () {
                $(this).dialog("close");
              },
            },
          ],
        });
      }

      /**
       * Fetches and displays the details for a specific order number
       * 
       * Here we call create_order_summary with false as last param, so the "View Details"
       * button is not generated again (since we are already viewing the details)
       */
      function fetchOrderDetails(ordernum) {
        const orderList = document.getElementById("orderlist");
        orderlist.innerHTML = "";

        searchState.filter = null;
        searchState.orderNum = ordernum;
        searchState.email = null;

        fetch(`../php/admin_fetchDetails.php`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(ordernum),
        })
          .then((response) => response.json())
          .then((orderData) => {
            if (orderData.error) {
              orderlist.insertAdjacentHTML(
                "beforeend",
                `
                <p style="margin-top: 10px;">${orderData.error}</p>
                `
              );
              return;
            }

            if (!orderData) {
              orderlist.insertAdjacentHTML(
                "beforeend",
                `
                <p style="margin-top: 10px;">No Orders Found</p>
                `
              );
              return;
            }

            const date = new Date(orderData.Date).toDateString();

            // ( OrderNum, Email, date, OrderStatus, showDetails )
            const orderSummary = create_order_summary(
              orderData.OrderNum,
              orderData.Email,
              date,
              orderData.OrderStatus,
              false
            );
            orderlist.insertAdjacentHTML("afterbegin", orderSummary);

            const orderDetailsArr = create_order_details(orderData);
            orderDetailsArr.forEach((orderItem) => {
              orderlist.insertAdjacentHTML("beforeend", orderItem);
            });
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      
      /**
       * Check if updateItemsMade should be run, and if so run it
       * Incoming object
       * {
       *  'orderNum': ${details.orderNum},
       *  'fabricName': '${value.item}',
       *  'size': '${item.Size}',
       *  'numOrdered': ${value.qnty},
       *  'numMade': ${value.made},
       *  'addOrSub': 'add || sub'
       * }
       */
      function updateItemsMade(obj) {
        const orderList = document.getElementById("orderlist");

        if (obj.addOrSub === "sub") {
          if (obj.numMade === 0) {
            // console.log("Cant go lower")
          } else {
            let updateItemsMade = obj.numMade - 1;
            let arr = [
              obj.orderNum,
              obj.fabricName,
              obj.size,
              updateItemsMade,
            ];
            updateItemsMadeFetch(arr);
          }
        } else if (obj.addOrSub === "add") {
          if (obj.numMade === obj.numOrdered) {
            // console.log("Cant go higher")
          } else {
            let updateItemsMade = obj.numMade + 1;
            let arr = [
              obj.orderNum,
              obj.fabricName,
              obj.size,
              updateItemsMade,
            ];
            updateItemsMadeFetch(arr);
          }
        }
      }

      /**
       * Updates the items made for an item in an order
       * then refreshes the order details
       *
       * Incoming array: [orderNum, "fabricName", "size", updateItemsMade];
       */
      function updateItemsMadeFetch(arr) {
        fetch(`../php/admin_updateItemsMade.php`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(arr),
        })
        .then((response) => response.json())
        .then((data) => {
          fetchOrderDetails(searchState.orderNum);
        })
        .catch((error) => {
          console.error("Error:", error);
        });
      }

      /**
       * Updates the status of an order then refreshes view
       *
       * Incoming array: [orderNum, status];
       */
      function updateStatus(orderNumSt) {
        const orderList = document.getElementById("orderlist");
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
          if (searchState.filter) {
            searchByStatus(searchState.filter);
          } else if (searchState.orderNum) {
            fetchOrderDetails(searchState.orderNum);
          } else if (searchState.email) {
            searchByEmail(searchState.email)
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
      }
    </script>
  </body>
</html>
