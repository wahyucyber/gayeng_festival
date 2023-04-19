class App {
    constructor() {
        this.baseUrl = $(`meta[name=baseUrl]`).attr(`content`)
        this.authorization = $(`meta[name=authorization]`).attr(`content`)
    }

    api(params) {
        axios({
            url:  this.baseUrl + (params.url.replace(this.baseUrl, ``)).replace(this.baseUrl.replace(`www.`, ``), ``),
            method: params.method ? params.method : `GET`,
            headers: {
                "Accept": "application/json",
                "Content-Type": params.content_type ? params.content_type : "application/json",
                "Authorization": this.authorization
            },
            data: params.data
        }).then(e => {
            params.success(e.data)
        }).catch(err => {
            let errCode = err.response ? err.response.status : ``

            if (errCode == 400) {
                if (err.response.data.message.constructor === Object) {
                    params.error(err.response.data)
                }else {
                    this.alertDanger(err.response.data.message)
                }
            }else if (errCode == 401) {
                $(".logout").trigger("click")
            }else if (err.response.status == 429) {
                this.alertWarning(`Too many API requests, please wait 1 more minute for data access!`)
            }else {
                this.alertWarning(`Maaf, terjadi kesalahan system!`)
                console.log(err)
            }
        })
    }

    apiSelect2(params) {
        $(params.element).select2({
            theme: params.theme ? params.theme : "bootstrap-5",
            width: '100%',
            dropdownParent: params.dropdownParent ? params.dropdownParent : "",
            ajax: {
                url: this.baseUrl + params.url.replace(this.baseUrl, ``),
                method: params.method ? params.method : `GET`,
                headers: {
                    "Accept": "application/json",
                    "Authorization": this.authorization,
                    // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: e => {
                    return {
                        term: e.term,
                        page: e.page || 1,
                        ...params.data
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.data.items,
                        pagination: {
                            more: data.data.pagination.more
                        }
                    };
                }
            }
        })
    }

    alertSuccess(text) {
        Swal.fire({
            icon: 'success',
            title: 'Good job!',
            text: text
        })
    }

    alertWarning(text) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: text
        })
    }

    alertDanger(text) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: text
        })
    }

    alertConfirm(params) {
        Swal.fire({
            title: params.title,
            text: params.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: params.confirmButton ? params.confirmButton : 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                params.isConfirmed()
            }
        })
    }

    rupiah(number) {
        return `IDR ${ parseInt(number).toLocaleString("id-ID") }`
    }

    numberFormat(number) {
        return parseInt(number).toLocaleString("id-ID")
    }

    dateTimeFormat(value) {
        const format = (e) => {
            if (e < 10) {
                return `0${ e }`
            }else {
                return e
            }
        }

        let newDate = new Date(value.slice(0, -1))
        let date = format(newDate.getDate())
        let month = format(newDate.getMonth() + 1)
        let fullYear = newDate.getFullYear()

        let hours = format(newDate.getHours())
        let minutes = format(newDate.getMinutes())

        return `${ fullYear }-${ month }-${ date } ${ hours }:${ minutes }`
    }

    formData(element) {
        return new FormData($(element)[0])
    }

    formFilter(element) {
        let formFilter = $(element).serializeArray()

        let filter = []

        $.each(formFilter, function (index, value) {
            filter[value[`name`]] = value[`value`]
        })

        return filter
    }
}

var app = new App
