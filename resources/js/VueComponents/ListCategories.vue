<template>
    <div class="list-categories">
        <div class="container">
            <h2>Categorii</h2>
            <button type="button" id="addCategory" class="btn btn-outline-primary" @click="addCategoryFieldActive = !addCategoryFieldActive">Adaugă Categorie</button>
            <slide-up-down :active="addCategoryFieldActive" :duration="500">
                <div class="row">
                    <div class="col-12">
                        <div class="form-row">
                            <div class="form-group col-9 col-lg-10" style="padding-right: 0;">
                                <input type="text" class="form-control addPlaceholder" placeholder="Nume categorie..." v-model="addInput">
                            </div>
                            <div class="form-group col-3 col-lg-2" style="padding-left: 0;">
                                <button class="form-control addButton" @click="addCategory">Adaugă</button>
                            </div>
                        </div>
                    </div>
                </div>
            </slide-up-down>
            <div class="loader">
                <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nume</th>
                        <th scope="col">Articole</th>
                        <th></th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td style="text-align: center;">{{ category.posts }}</td>
                        <td><a><button type="button" class="btn btn-warning btn-sm" style="color: #FFFFFF;">Editează</button></a></td>
                        <td><button type="button" class="btn btn-danger btn-sm" v-b-modal.deleteModal @click="setCategoryIdDelete(category.id)">Șterge</button></td>
                    </tr>
                </tbody>
            </table>
            <div>
                <b-modal
                    id="deleteModal"
                    title="Ștergere Categorie"
                    ok-title="Ștergere"
                    ok-variant="danger"
                    cancel-title="Anulează"
                    @hide="disableForm = false"
                    @cancel="disableForm = false"
                    @ok="deleteCategory">
                    <p class="my-4">Ești sigur că vrei să ștergi această categorie?</p>
                    <p class="my-4">Odată ștearsă, aceasta nu va mai putea fi recuperată.</p>
                </b-modal>
            </div>
        </div>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';
    import SlideUpDown from 'vue-slide-up-down';

    export default {
        name: 'list-categories',
        components: { BounceLoader, SlideUpDown },
        mounted: function () {
            this.fetchCategories();
        },
        data() {
            return {
                categories: {},
                addInput: "",
                loading: false,
                disableForm: true,
                addCategoryFieldActive: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            };
        },
        methods: {
            /**
             * Fetch the categories available to be displayed on the first page.
             */
            fetchCategories: function () {
                this.loading = true;
                let getCategoriesUrl = "/getCategories";

                axios.get(getCategoriesUrl)
                    .then((response) => {
                        this.categories = response.data.response;
                    }).catch((error) => {
                    console.log(error);
                }).then(() => {
                    this.loading = false;
                });
            },
            addCategory: function () {
                if (this.addInput.length) {
                    this.loading = true;
                    let parameters = {
                        name : this.addInput,
                    };

                    let url = '/categories/create';
                    axios.post(url, parameters)
                        .then(function (response) {
                            if (response.data.success) {
                                this.fetchCategories();
                                this.toast('b-toaster-bottom-right', "success", "Succes!", "Categoria a fost adăugată cu succes.");
                            } else {
                                this.toast('b-toaster-bottom-right', "danger", "Oops!", "A apărut o eroare la adăugarea categoriei. Te rog încearcă din nou mai târziu.");
                            }
                        }.bind(this)).catch(function (error) {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "A apărut o eroare la adăugarea categoriei. Te rog încearcă din nou mai târziu.");
                    }.bind(this)).finally(function () {
                        this.loading = false;
                    }.bind(this));
                } else {
                    this.toast('b-toaster-bottom-right', "danger", "Oops!", "Te rog completează numele categoriei.");
                }
            },
            setCategoryIdDelete: function (id) {
                this.idDelete = id;
            },
            deleteCategory: function () {
                this.loading = true;
                let parameters = {
                    id : this.idDelete,
                };

                let url = '/categories/delete/' + this.idDelete;
                axios.post(url, parameters)
                    .then(function (response) {
                        if (response.data.success) {
                            this.fetchCategories();
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Categoria a fost ștearsă cu succes.");
                        } else {
                            _.forEach(response.data.response, function(error) {
                                this.toast('b-toaster-bottom-right', "danger", "Oops!", error);
                            }.bind(this));
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "A apărut o eroare la ștergerea categoriei. Te rog încearcă din nou mai târziu.");
                }.bind(this)).finally(function () {
                    this.loading = false;
                }.bind(this));
            },
            /**
             * Create display message using "toast" bootstrap-vue component.
             */
            toast: function (toaster, variant, title, message) {
                this.$bvToast.toast(message, {
                    title: title,
                    variant: variant,
                    toaster: toaster,
                    solid: true,
                    appendToast: true,
                })
            },
        },
    }
</script>

<style>

</style>
