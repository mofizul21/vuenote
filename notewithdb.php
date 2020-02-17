<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple Note CRUD with Vue JS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>

<body>
    <div id="app">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="text-center m-5">Simple Note</h2>
                </div>

            </div>
            <div class="row ">
                <div class="col-md-6">
                    <div class="card mt-1">
                        <div class="card-body">
                            <h3>Add a Note</h3>

                            <div class="alert alert-danger" v-if="errorText">{{errorText}}</div>
                            <div class="alert alert-success" v-if="successText">{{successText}}</div>

                            <input type="text" name="" class="form-control" v-model="title" placeholder="Title">

                            <textarea name="" cols="30" class="form-control mt-2" v-model="description" placeholder="Description"></textarea>

                            <button type="button" class="btn btn-success float-right mt-2" @click="addNote">Add Note</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-1" v-for="(note, index) in notes.data">
                        <div class="card-body">
                            <button class="btn btn-danger float-right" @click="deleteNote(note.id)">X</button>

                            <button class="btn btn-info float-right mr-1" @click="editNote(note.id)">Edit</button>

                            <h3>{{note.title}}</h3>
                            <p>{{note.description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mt-1">
                                    <div class="card-body">

                                        <div class="alert alert-danger" v-if="errorText">{{errorText}}</div>
                                        <div class="alert alert-success" v-if="successText">{{successText}}</div>

                                        <input type="text" name="" class="form-control" v-model="title" placeholder="Title">

                                        <textarea name="" cols="30" class="form-control mt-2" v-model="description" placeholder="Description"></textarea>

                                        <button type="button" class="btn btn-success float-right mt-2" @click="updateNote">Update Note</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        new Vue({
            el: '#app',
            data: {
                title: '',
                description: '',
                selectedNote: 0,
                notes: [],
                errorText: '',
                successText: '',
            },
            mounted() {
                var vm = this;
                // Fetch notes from database
                vm.fetchNotes();

            },

            methods: {
                addNote() {
                    let title = this.title;
                    let description = this.description;
                    let vm = this;

                    if (title.length > 0 && description.length > 0) {

                        const singleNote = new URLSearchParams();
                        singleNote.append('title', title);
                        singleNote.append('description', description);
                        singleNote.append('author_id', 1);

                        axios.post('http://localhost/vuetut/api/add-note.php', singleNote)
                            .then(function(response) {
                                vm.fetchNotes();
                            })
                            .catch(function(error) {
                                console.log(error);
                            });


                        this.successText = "Note added successfully!";
                        this.title = "";
                        this.description = "";
                        this.errorText = "";

                    } else {
                        this.errorText = "Fields can not be empty!";
                        this.successText = ""
                    }
                },

                deleteNote(noteIndex) {
                    var vm = this;
                    const singleNote = new URLSearchParams();
                    singleNote.append('id', noteIndex);

                    axios.post('http://localhost/vuetut/api/delete-note.php', singleNote)
                        .then(function(response) {
                            vm.fetchNotes();
                        })
                        .catch(function(error) {
                            console.log(error);
                        });

                    vm.successText = "The note has been deleted!";
                    vm.errorText = "";

                },

                editNote(noteIndex) {
                    $('#editModal').modal('show');
                    var vm = this;
                    vm.selectedNote = noteIndex;

                    axios.get('http://localhost/vuetut/api/show-note.php?id=' + noteIndex)
                        .then(function(response) {
                            // handle success
                            vm.title = response.data.data.title;
                            vm.description = response.data.data.description;
                            vm.successText = "";
                            vm.errorText = "";
                        })
                        .catch(function(error) {
                            // handle error
                            console.log(error);
                        });
                },

                updateNote() {
                    var vm = this;

                    const singleNote = new URLSearchParams();
                    singleNote.append('title', vm.title);
                    singleNote.append('description', vm.description);
                    singleNote.append('author_id', 1);
                    singleNote.append('id', vm.selectedNote);

                    axios.post('http://localhost/vuetut/api/edit-note.php', singleNote)
                        .then(function(response) {
                            // handle success
                            vm.fetchNotes();

                            vm.successText = "Note Updated Successfully!";
                            vm.title = "";
                            vm.description = "";
                            vm.errorText = "";
                            $('#editModal').modal('hide');
                        })
                        .catch(function(error) {
                            // handle error
                            console.log(error);
                        });
                },

                fetchNotes() {
                    var vm = this;

                    axios.get('http://localhost/vuetut/api/notes.php')
                        .then(function(response) {
                            // handle success
                            vm.notes = response.data;
                        })
                        .catch(function(error) {
                            // handle error
                            console.log(error);
                        });
                }
            },

        });
    </script>
</body>

</html>