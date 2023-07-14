const btn_submit = document.querySelector(".btn-submit");

let res;

const submitted = async function () {
    let form_data = new FormData(document.querySelector('#myForm'));

    let user = await get_textref(form_data);

    fetch('http://localhost/Chkela/payment/accept_payment.php', {
        method: 'POST',
        body: JSON.stringify(user)
    }).then(async response => {
        let apiResponse = await response.json();
        return apiResponse;
    }).then(res => { window.location.href = `${res.data.checkout_url}`; }).catch(error => { console.log(error) });
};

const get_textref = async function (form_data) {
    try {
        let res = await fetch('http://localhost/Chkela/payment/get_textref.php', {
            method: 'POST',
            body: form_data
        });

        if (res.ok) {
            let result = await res.json();

            return result;
        }
    } catch (error) {
        console.log(error);
    }
}

btn_submit.addEventListener("click", submitted);
