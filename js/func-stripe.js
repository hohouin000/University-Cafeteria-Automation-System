const stripe = Stripe("pk_test_51MBGiuHGbqwDRBAKxQDTW5qr9dddoqz5z0YpgdaLD37hNjMCoFGDP7NzA3CQgVZhDF1id0PRi4RW51tpJQixRYBC00fbN4bNTA")
const btn = document.querySelector('#btn-pay-online')
btn.addEventListener('click', ()=>{
    fetch('/cart.php',{
        method:"POST",
        headers:{
            'Content-Type' : 'application/json',
        },
        body: JSON.stringify({})
    }).then(response=> response.json())
    .then(payload => {
        stripe.redirectToCheckout({sessionId: payload.id})
    })
})