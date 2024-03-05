<template>
    <div>
        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
            <v-tab
                v-for="(station, i) in store.user.stations"
                :key="i"
                :value="station.name"
                @click="gitStation(station)"
            >
                {{ station.name }}
            </v-tab>
        </v-tabs>
        <v-window v-model="tab">
            <v-window-item
                v-for="(station, i) in store.user.stations"
                :key="i"
                :value="station.name"
            >
                <h5
                    v-if="station.reports?.length == 0"
                    class="my-3 text-center"
                >
                    لا يوجد تقارير
                </h5>

                <v-card
                    v-else
                    color="grey-lighten-2"
                    class="my-3 p-3"
                    v-for="(report, i) in stationIN.reports"
                    :key="i"
                >
                    <router-link class="nav-link" :to="`/report/${report.id}`">
                        <div id="textShare">
                            <v-card-title>
                                <v-chip class="float-start">{{
                                    report.user.name
                                }}</v-chip>
                            </v-card-title>
                            <v-card-text class="my-2 text-content">{{
                                report.body
                            }}</v-card-text>
                            <v-chip class="m-1">{{
                                date(report.created_at)
                            }}</v-chip>
                            <v-chip class="m-1">{{
                                timeSinceReport(report.created_at)
                            }}</v-chip>
                        </div>
                    </router-link>
                    <div>
                        <div class="report-actions">
                            <span class="mdi mdi-comment-outline"></span>
                            <span class="m-1">{{
                                report.comments.length
                            }}</span>
                            <div
                                v-if="report.user.id == store.user.id"
                                class="float-right mx-4"
                            >
                                <span
                                    class="clickd mdi mdi-delete-outline"
                                    @click="deleted(report.id)"
                                ></span>
                            </div>
                            <span
                                class="mx-3 clickd mdi mdi-share-outline"
                                @click="share(report)"
                            ></span>
                        </div>
                    </div>
                    <v-card-actions class="mt-2">
                        <v-textarea
                            width="100%"
                            color="grey-lighten-5"
                            variant="outlined"
                            :rows="1"
                            auto-grow
                            :label="$t('comment')"
                            v-model="report.comment"
                        />
                        <v-btn
                            icon="mdi-send"
                            @click="addComment(report)"
                        ></v-btn>
                    </v-card-actions>

                    <v-btn
                        class="mb-2"
                        @click="report.showComments = !report.showComments"
                        >{{
                            report.showComments
                                ? $t("hideComments")
                                : $t("showComments")
                        }}</v-btn
                    >
                    <v-card
                        class="mb-3 p-3"
                        v-for="(comment, i) in report.comments.slice(
                            0,
                            report.showComments ? report.comments.length : 1
                        )"
                        :key="i"
                    >
                        <v-chip>{{ comment.user?.name }}</v-chip>
                        <div class="text-content">{{ comment.body }}</div>
                        <span class="float-right">{{
                            timeSinceReport(comment.created_at)
                        }}</span>
                    </v-card>
                </v-card>
            </v-window-item>
        </v-window>
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
const stationIN = ref({});
const dialog = ref(false);
const tab = ref(0);
const stationsNav = ref(router.params.id);
console.log(stationsNav.value);
onMounted(() => {
    axios
        .get(`Stations/${router.params.id}`)
        .then((res) => {
            stationIN.value = res.data;
            console.log(stationIN.value);
        })
        .catch(() => {
            stationIN.value = "المحطة غير موجوده";
        });
    console.log(store.user.stations);
});
function gitStation(station) {
    axios
        .get(`Stations/${station.id}`)
        .then((res) => {
            stationIN.value = res.data;
            console.log(stationIN.value);
        })
        .catch(() => {
            stationIN.value = "المحطة غير موجوده";
        });
}
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

<style scoped></style>
