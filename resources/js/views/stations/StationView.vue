<template>
    <div>
        {{ station }}
    </div>
</template>
<script setup>
import { useRoute } from "vue-router";
const router = useRoute();
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import { onMounted, ref } from "vue";
import axios from "axios";
import moment from "moment";
moment.locale(localStorage.language + "-dz");
const station = ref('');
const dialog = ref(false);
onMounted(() => {
    axios
        .get(`stations/4}`)
        .then((res) => {
            station.value = res.data;
        })
        .catch(() => {
            station.value = "المحطة غير موجوده";
        });
});
function timeSinceReport(time) {
    return moment(time).fromNow();
}
function date(d) {
    return moment(d).format("dddd :- h:mm A - MM/DD ");
}
function addComment(station) {
    const obComment = {
        body: station.comment,
        station_id: station.id,
        user_id: store.user.id,
    };
    axios
        .post(`Comments`, obComment)
        .then(() => {
            store.getReports();
            getReport();
            report.comment = "";
        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
        });
}
function deleted() {
    dialog.value = true;
}
function deleteReport() {
    axios.delete(`reports/${router.params.id}`).then(() => {
        dialog.value = false;
        store.startSnack("success", "back", "success");
        store.getReports();
    });
}
window.addEventListener("beforeunload", function (event) {
    if (event.pageYOffset === 0) {
        event.preventDefault();
        event.returnValue = "";
        getReport();
    }
});
</script>
<style scoped>
.comment-length {
    font-size: 25px;
}
</style>
