@extends('_layout.app', [
    "title" => "Berita",
    "dashboard" => true
])

@section('content')
    <div class="card">
        <div class="card-header card-primary">
            <h5 class="card-title">Data Berita</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-condensed table-bordered" id="news">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        class News extends App {
            constructor() {
                super()

                this.news

                this.get()
            }

            get() {
                this.news = $(`table#news`).laravelTable({
                    url: `${ this.baseUrl }/api/admin/news`,
                    headers: {
                        'Authorization': this.authorization,
                        'Accept': 'application/json'
                    },
                    search: {
                        placeholder: `Cari judul...`
                    },
                    columns: [
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `<img src="${ e.picture }" style="width: 100px;" alt="${ e.title }" />`
                            }
                        },
                        {
                            data: `title`
                        },
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `
                                    <div class="btn-group">
                                        <a href="{{ env("APP_URL") }}/admin/event/${ e.slug }/update" class="btn btn-primary btn-icon"><i class="ri-edit-circle-line"></i></a>
                                        <button type="button" class="btn btn-danger btn-icon destroy" data-id="${ e.slug }" data-title="${ e.title }"><i class="ri-delete-bin-6-line"></i></button>
                                    </div>
                                `
                            }
                        }
                    ]
                })
            }

            destroy(slug) {
                this.api({
                    url: `/api/admin/news/${ slug }/destroy`,
                    method: `DELETE`,
                    success: e => {
                        this.news.fresh()

                        this.alertSuccess(`Data has been destroy!`)
                    }
                })
            }
        }

        var news = new News

        $(document).on(`click`, `button.destroy`, function() {
            let id = $(this).data(`id`)
            let title = $(this).data(`title`)

            app.alertConfirm({
                title: `Are you sure?`,
                text: `To delete the ${ title }.`,
                isConfirmed: e => {
                    news.destroy(id)
                }
            })
        })
    </script>
@endsection
