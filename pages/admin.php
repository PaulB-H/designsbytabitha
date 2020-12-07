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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../styles/admin.css">
  </head>
  <body>
    <h3>DBT Admin</h3>
    <button onclick="location = './session.php' ">Back</button>
    <div id="filterBtns">
      <p class="tac">Filters</p>
      <button onclick="fetchAndDisplayOrders('all')">All</button>
      <button onclick="fetchAndDisplayOrders('pending')">Pending</button>
      <button onclick="fetchAndDisplayOrders('wip')">WIP</button>
      <button onclick="fetchAndDisplayOrders('canceled')">Canceled</button>
      <button onclick="fetchAndDisplayOrders('complete')">Complete</button>
    </div>
    <hr>
    Order # <!---or Email---> <input onchange="searchQuery = this.value" type="text" name="" id="orderOrEmail" />
    <button onclick="fetchAndDisplaySingle(searchQuery)">Search</button>
    <hr />
    <div id="orderlist"></div>

    <div id="dialog-confirm" title="Confirm Delete Order" style="visibility: hidden;">
      
    </div>

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
              // orderlist.innerHTML = "";
              orderlist.insertAdjacentHTML(
                "afterbegin",
                `
                <h3>${filter.toUpperCase()} Orders</h3>
                <hr>
                `
              );
              if (data.length > 0) {
                data.forEach(function (value, index) {
                  let date = new Date(value.Date);
                  date = date.toDateString(); 
                  orderlist.insertAdjacentHTML(
                    "beforeend",
                    `
                    <p>Order # <strong>${value.orderNum}</strong></p>
                    <p>&nbsp;${value.Email}</p>
                    <p>&nbsp;&nbsp;${date}</p>
                    <br>
                    <button onclick="fetchAndDisplaySingle(${value.orderNum})">View Details</button>
                    <br><br>
                    <p>Status: <strong style="background: yellow; padding: 3px;">${value.orderStatus}</strong>
                      <br>
                      <p>Set Status:</p>
                      <div class="updateStatusBtns">
                        <button onclick="updateStatus([${value.orderNum}, 'Pending'])">Pending</button>
                        <button onclick="updateStatus([${value.orderNum}, 'WIP'])">WIP</button>
                        <button onclick="updateStatus([${value.orderNum}, 'Canceled'])">Canceled</button>
                        <button onclick="updateStatus([${value.orderNum}, 'Complete'])">Complete</button>
                      </div>
                    </p>
                    <button class="deleteBtn" onclick="deleteOrder(${value.orderNum})">Delete</button>
                    <br><br>
                    <hr>
                    `
                  );
                });
              } else { orderlist.insertAdjacentHTML("beforeend",
                `
                <p>No orders found</p>
                `
              )}
              resolve(data);
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        });
      }
      fetchAndDisplayOrders("all");

      function deleteOrder (num) {
        $( "#dialog-confirm" ).dialog({
          resizable: false,
          height: "auto",
          width: "auto",
          modal: true,
          title: `Delete Order ${num} ?`,
          buttons: {
            "YES \n Delete": function() {
              orderlist.innerHTML = "";
              $( this ).dialog( "close" );
              fetch(`../php/admin_deleteOrder.php`, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify(num),
              })
                .then((response) => response.json())
                .then((data) => {
                  console.log(data)
                  if(singleOrder === false){
                    fetchAndDisplayOrders(lastFilter);
                  } else {
                    fetchAndDisplaySingle(lastOrder);
                  }
                })
            },
            "NO \n Cancel": function() {
              $( this ).dialog( "close" );
            }
          }
        });
      }

      function fetchAndDisplaySingle (ordernum) {
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
            // First array item is order details
            if(data.length > 0){
              let details = data.shift();
              let date = new Date(details.Date)
              date = date.toDateString();
              orderlist.insertAdjacentHTML(
                "afterbegin",
                `
                <h3>Order # ${details.orderNum} Details</h3>
                <p>&nbsp; ${details.Email}</p>
                <p>&nbsp;&nbsp; ${date}</p>
                <h3 style="background: yellow;">Status: ${details.orderStatus}</h3>
                <br>
                <div class="updateStatusBtns">
                  <button onclick="updateStatus([${details.orderNum}, 'Pending'])">Pending</button>
                  <button onclick="updateStatus([${details.orderNum}, 'WIP'])">WIP</button>
                  <button onclick="updateStatus([${details.orderNum}, 'Canceled'])">Canceled</button>
                  <button onclick="updateStatus([${details.orderNum}, 'Complete'])">Complete</button>
                </div>
                <button class="deleteBtn" onclick="deleteOrder(${details.orderNum})">Delete</button>
                <br><br>
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
                  <button onclick="updateItemsMade(
                    {
                      'orderNum': ${details.orderNum}, 
                      'fabricName': '${value.item}', 
                      'numOrdered': ${value.qnty}, 
                      'numMade': ${value.made}, 
                      'addOrSub': 'sub'
                    }
                  )">-1</button>
                  <button onclick="updateItemsMade(
                    {
                      'orderNum': ${details.orderNum}, 
                      'fabricName': '${value.item}', 
                      'numOrdered': ${value.qnty}, 
                      'numMade': ${value.made}, 
                      'addOrSub': 'add'
                    }
                  )">+1</button>
                  
                  <br><br>
                  <hr>
                  `
                );
              });
            } else {
              orderlist.insertAdjacentHTML("beforeend",
                `
                <p>No Orders Found</p>
                `
              )
            }
            // resolve(data);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      // Incomming object for updateItemsMade:
      // {
      //  'orderNum': ${details.orderNum}, 
      //  'fabricName': '${value.item}', 
      //  'numOrdered': ${value.qnty}, 
      //  'numMade': ${value.made}, 
      //  'addOrSub': 'add || sub'
      // }
      function updateItemsMade (obj) {
        if (obj.addOrSub === 'sub') {
          if (obj.numMade === 0) {
            // console.log("Cant go lower")
          } else {
            orderlist.innerHTML = "";
            let updateItemsMade = obj.numMade -1;
            let arr = [
              obj.orderNum,
              `${obj.fabricName}`,
              updateItemsMade,
            ];
            updateItemsMadeFetch(arr)
          }
        } else if (obj.addOrSub === 'add') {
          if (obj.numMade === obj.numOrdered) {
            // console.log("Cant go higher")
          } else {
            orderlist.innerHTML = "";
            let updateItemsMade = obj.numMade +1;
            let arr = [
              obj.orderNum,
              `${obj.fabricName}`,
              updateItemsMade,
            ];
            updateItemsMadeFetch(arr)
          }
        }
      }

      // Incoming Array: [orderNum, "fabricName", udpateItemsMade]
      function updateItemsMadeFetch (arr) {
        fetch(`../php/admin_updateItemsMade.php`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(arr),
        })
          .then((response) => response.json())
          .then((data) => {
            fetchAndDisplaySingle(lastOrder);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      // orderNumSt = array [orderNum, status];
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
