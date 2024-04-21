<?php
  include("../php/session_start.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - Designs by Tabitha</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    #container {
      height: 100vh;
      display: flex;
      justify-content: center;
      background-image: url(../images/vector/header.svg);
      background-size: cover;
    }
    #content {
      background: white;
      box-shadow: 0 0 10px;
      padding: 20px;
      min-width: 300px;
    }
    .order-item {
      padding: 10px;
    }
    .orderBtns {
      display: flex;
      justify-content: space-evenly;
      text-align: center;
      margin: 5px;
      padding: 3px;
    }
    .orderBtns button{
      padding: 5px;
    }

    p {
      font-family: sans-serif;
    }

    .order-item p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div id="container">
    <div id="content">

      <div style="margin: 10px">
        <button style="width: 100%; padding: 5px" onclick="location.href = './session.php';">
          Back to Account
        </button>
      </div>

      <h1>My Orders</h1>
      <div id="orderList"></div>
      <div id="orderDetails"></div>

    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous"></script>

  <script>
    function fetchMyOrders(){
      return new Promise(resolve =>{
        fetch('../php/get_my_orders.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        })
        .then(response => response.json())
        .then(data => {
          resolve(data);
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    }

    function fetchOrderDetails(orderNum){
      return new Promise(resolve =>{
        fetch('../php/get_order_details.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderNum),
        })
        .then(response => response.json())
        .then(data => {
          resolve(data)
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    };
    
    function fetchDeleteOrder(orderNum){
      return new Promise(resolve =>{
        fetch('../php/post_delete_order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderNum),
        })
        .then(response => response.json())
        .then(data => {
          resolve(data)
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    };

    function confirmDeleteOrder(orderNum) {
      return new Promise(resolve => {
        iziToast.show({
          timeout: 0,
          theme: 'dark',
          icon: 'icon-person',
          title: 'Cancel Order?',
          position: 'center',
          overlay: true,
          buttons: [
            [`<button>Yes - Cancel order ${orderNum}</button>`, function (instance, toast) {
              deleteAndRenderOrders(orderNum);
              resolve();
              iziToast.destroy();
            }],
            ['<button> NO </button>', function (instance, toast) {
              iziToast.destroy();
            }]
          ],
        });
      });
    }

    function handleDeleteOrder(orderNum) {
      confirmDeleteOrder(orderNum).then(() => fetchAndRenderOrders());
    }

    async function fetchAndRenderOrders(){
      const result = await fetchMyOrders();
      let orderListDiv = document.getElementById("orderList")
      orderListDiv.innerHTML = "";

      document.getElementById("orderDetails").innerHTML = "";

      result.forEach(function(value, index){
        orderListDiv.insertAdjacentHTML("beforeend", 
        `
          <div class="order-item">
            <p>Order Number: ${value.OrderNum}</p>
            <p>Date: ${value.Date.split(" ")[0]}</p>
            <p>Status: ${value.OrderStatus}</p>
            <div class="orderBtns">
              <button onclick="fetchAndRenderDetails(${value.OrderNum})">
                View
                <br />
                Details
              </button>
              <button onclick="handleDeleteOrder(${value.OrderNum})">
                Cancel
                <br />
                Order
              </button>
            </div>
          </div>
          <hr>
        `)
      })

      if(document.getElementById("orderList").children.length === 0){
        document.getElementById("orderList").insertAdjacentHTML("beforeend",
        `
          <h1>No orders found!</h1>
        `)
      }
            
    };
    fetchAndRenderOrders();

    async function fetchAndRenderDetails(orderNum){
      const result = await fetchOrderDetails(orderNum)

      const orderListDiv = document.getElementById("orderList");
      orderListDiv.innerHTML = "";
      orderListDiv.insertAdjacentHTML("afterbegin", `
        <button onclick="fetchAndRenderOrders()" style="
          margin: 20px auto;
          padding: 5px;
        ">
          Show All Orders
        </button>
      `)

      let orderDetailsDiv = document.getElementById("orderDetails");
      orderDetailsDiv.innerHTML = "";
      orderDetailsDiv.insertAdjacentHTML("afterbegin",
      `
        <hr>
        <h3 style="margin: 20px 0 10px 0; font-family: sans-serif">Order <u>${orderNum}</u> Details
          <button onclick="handleDeleteOrder(${orderNum})" style="
            float: right;
            margin: 5px;
            background-color: #f70e0e52;
            border-radius: 5px;
            border: none;
            padding: 5px;
            font-weight: bold;
          ">
            Cancel
            <br />
            Order
          </button>
        </h3>
      `)

      result.forEach((item, index) => {
        document.getElementById("orderDetails").insertAdjacentHTML("beforeend",
        `
          <div class="order-item">
            <p>Mask ${index + 1}</p>
            <p>Fabric: ${item.fabric}</p>
            <img src="../${item.imgUrl}" style="height: 100px; width: 100px; border-radius: 5px">
            <p>Size: ${item.size}</p>
            <p>Quantity: ${item.qnty}</p>
            <br />
          </div>
          <hr />
        `)
      })
    }

    async function deleteAndRenderOrders(orderNum){
      const result = await fetchDeleteOrder(orderNum);
      fetchAndRenderOrders();
      document.getElementById("orderDetails").innerHTML = "";
    }
  </script>
</body>
</html>