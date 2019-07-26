Vue.component('modal', {
    template: '#modal-template'
})

var crudapp = new Vue({
    delimiters: ['v{', '}e'],
    el: "#crudapp",
    data: {
        name: { value: "", error: false },
        email: { value: "", error: false },
        city: { value: "", error: false },
        country: { value: "", error: false },
        job: { value: "", error: false },
        id: "",
        contacts: [],
        errors: [],
        showModal: false,
        modalAddButton: "inline-block",
        modalUpdateButton: "none"
    },
    mounted: function() {
        this.getContacts()
    },
    methods: {
        getContacts: function() {
            axios.get('api/contacts').then(function(response) {
                crudapp.contacts = response.data
            }).catch(function (error) {
                console.log(error)
            })
        },
        createContact: function() {
            let formData = new FormData();

            formData.append("name", this.name.value)
            formData.append("email", this.email.value)
            formData.append("city", this.city.value)
            formData.append("country", this.country.value)
            formData.append("job", this.job.value)

            var contact = {};
            formData.forEach(function(value, key) {
                contact[key] = value
            })

            axios({
                method: "post",
                url: "api/contacts",
                data: formData,
                config: {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                }
            }).then(function(response) {
                // handle success
                contact["id"] = response.data.id
                crudapp.contacts.push(contact)
                crudapp.resetForm()
            }).catch(function(error) {
                // handle error
                console.log(error)
            })
        },
        resetForm: function() {
            this.name = { value: "", error: "" }
            this.email = { value: "", error: "" }
            this.city = { value: "", error: "" }
            this.country = { value: "", error: "" }
            this.job = { value: "", error: "" }
            this.id = ""

            this.errors = []
            this.showModal = false
        },
        deleteContact: function(id) {
            axios({
                method: "delete",
                url: "api/contacts/" + id
            }).then(function(response) {
                // handle success
                crudapp.contacts.splice(crudapp.contacts.findIndex(obj => obj.id == id), 1)
                crudapp.resetForm()
            }).catch(function(error) {
                // handle error
                console.log(error)
            })
        },
        checkForm: function(id) {
            this.errors = []

            // validate fields
            if (this.name.value.length < 1) {
                this.errors.push("You must provide the Contacts Name")
                this.name.error = true
            }

            if (this.errors.length == 0) {
                if (id != undefined && id > 0) {
                    crudapp.updateContact(id)
                } else {
                    crudapp.createContact()
                }
            }
        },
        updateModal: function(id) {
            crudapp.resetForm()

            contact = this.contacts[crudapp.contacts.findIndex(obj => obj.id == id)]
            keys = ["name", "email", "city", "country", "job"]

            keys.forEach(function(key) {
                crudapp[key].value = contact[key]
            })

            crudapp.id = id
            crudapp.modalAddButton = "none"
            crudapp.modalUpdateButton = "inline-block"
            crudapp.showModal = true
        },
        updateContact: function(id) {
            attrs = ["name", "email", "city", "country", "job"]

            let formData = {}
            attrs.forEach(function(key) {
                formData[key] = crudapp[key].value
            })

            axios({
                method: "put",
                url: "api/contacts/" + id,
                data: formData,
                config: {
                    headers: {
                        "Content-Type": "application/json"
                    }
                }
            }).then(function(response) {
                // handle success
                contactIndex = crudapp.contacts.findIndex(obj => obj.id == id)

                attrs.forEach(function(key) {
                    crudapp.contacts[contactIndex][key] = formData[key]
                })
                crudapp.resetForm()
            }).catch(function(error) {
                console.log(error)
            })
        },
        cancelModal: function() {
            crudapp.showModal = false
            crudapp.resetForm()
            crudapp.modalAddButton = "inline-block"
            crudapp.modalUpdateButton = "none"
        }
    }
})
