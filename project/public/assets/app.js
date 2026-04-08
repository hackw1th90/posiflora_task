window.onload = () => {
    let app = new Vue(
        {
            el: '#app',
            data: {},
            methods: {
                getStats(shopId) {
                    axios.get('/shops/' + shopId + '/telegram/status').then((response) => {
                        document.getElementById("results").innerHTML = JSON.stringify(response.data)
                    }).catch((err) => {
                        document.getElementById("results").innerHTML = err
                    })
                },
                createOrder(shopId) {
                    let number = document.getElementById("form-order-number").value
                    let total = document.getElementById("form-order-total").value
                    let customerName = document.getElementById("form-order-customerName").value
                    axios.post('/shops/' + shopId + '/orders', {
                        number: number,
                        total: Number(total),
                        customerName: customerName,
                    }).then((response) => {
                        document.getElementById("results").innerHTML = JSON.stringify(response.data)
                    }).catch((err) => {
                        document.getElementById("results").innerHTML = err
                    })
                },
                updateToken(shopId) {
                    let botToken = document.getElementById("form-botToken").value
                    let chatId = document.getElementById("form-chatId").value
                    let enabled = document.getElementById("form-enabled").value === 'true'
                    axios.post('/shops/' + shopId + '/telegram/connect', {
                        botToken: botToken,
                        chatId: chatId,
                        enabled: enabled,
                    }).then((response) => {
                        document.getElementById("results").innerHTML = JSON.stringify(response.data)
                    }).catch((err) => {
                        console.log(err)
                        document.getElementById("results").innerHTML = err
                    })
                }
            }
        }
    )
}
