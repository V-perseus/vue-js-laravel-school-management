<template>
    <div class="es-index">
        <div class="logged"  v-if="$store.state.user">
            <div v-if="!$isMobile()">
                <div class="es-header">
                    <div class="es-container row">
                        <div class="es-header-logo">
                            <img class="header-logo-img" src="/img/logo.png" @click="goHome()">
                        </div>
                        <div class="es-header-main">
                            <Input suffix="ios-search" placeholder="Enter text" style="width: auto" />
                        </div>
                        <div class="es-header-profile d-flex">
                            <div class="clickable-profile-container ml-auto d-flex align-items-center" @click="showProfileModal">
                                <avatar :size="32" :src="$store.state.user.userAvatar" :username="$store.state.user.name" class="pr-0 mr-2"></avatar>
                                <span class="mr-2">{{$store.state.user.name}}</span>
                            </div>
                            <span><a href="/logout" style="color:#fff!important" onclick="return confirm('是否退出登录？')"> | 退出</a></span>
                        </div>
                    </div>
                </div>
                
                <div class="es-container">
                    <fab
                        :position="positionTopLeft"
                        :bg-color="bgColor"
                        :actions="fabActions"
                        @chat="chat"
                        @map="map"
                        @liveLecture="liveLecture"
                    ></fab>
                    
                    <div class="es-menu" v-if="$store.state.user">
                        <Menu>
                            <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i" v-if="permissionList.schoolName.read">
                                <template slot="title">
                                    <Icon type="ios-analytics" />
                                    {{permissionList.schoolName.resourceName}}
                                </template>
                                <MenuItem  :name="`${i}-${j}`" :to="`/${menuItem.name}`" v-for="(menuItem,j) in permissionList.data" :key="j" v-if="permissionList.data.length && menuItem.read">
                                    {{ menuItem.resourceName }}
                                </MenuItem>
                            </Submenu>
                        </Menu>
                    </div>
                    
                    <div class="es-router">
                        <router-view :key="$route.path"></router-view>
                    </div>
                </div>
                <div class="es-footer">
                    copyright &#169; All reserved school
                </div>
            </div>
            <div class="container-fluid w-100" v-else>
                <router-view></router-view>
            </div>
        </div>
        <div class="login-page" v-else>
            <div>
                <div class="header">
                    <div class="header-box">
                        <a href="#" class="header-logo"><img src="/img/logo_original.png" alt=""></a>
                        <div class="header-nav">
                            <a href="">iOS</a>
                            <a href="/downloads/apk/ESchool.apk">Android</a>
                            <a href="">Windows</a>
                            <a href="">Mac</a>
                        </div>
                    </div>
                </div>
                <div class="main">
                    <div class="describe">
                        <p>成若天性，习惯如自然。</p>
                        <p>成若天性，习惯如自然。</p>
                    </div>
                    <div class="login">
                        <Tabs name="main" value="name2">
                            <TabPane label="扫码登录" name="name1">
                            <div class="mb-3 mt-3 login-input">
                                    <Input type="text" v-model="register.name" placeholder="fullName">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input type="text" v-model="register.phoneNumber" placeholder="Phone Number">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input type="password" v-model="register.password" placeholder="******">
                                        <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                </div>
                                <div class="login_footer mb-2">
                                    <Button type="primary" long @click="userRegister" :disabled="isAdding" :loading="isAdding">{{isAdding ? '登录...' : '登录'}}</Button>
                                </div>
                            </TabPane>
                            <TabPane label="账户登录" name="name2">
                                <div class="mb-3 mt-3 login-input">
                                    <Input type="text" v-model="data.phoneNumber" placeholder="Phone Number">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input type="password" v-model="data.password" placeholder="******">
                                        <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                </div>
                                <div class="mb-2">
                                    <Checkbox v-model="policy"></Checkbox>
                                    <span>已阅读并同意<a href="#">《用户服务协议》和《隐私》</a></span>
                                </div>
                                <div class="login_footer mb-2">
                                    <Button type="primary" long @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? '登录...' : '登录'}}</Button>
                                </div>
                                <div style="width:100%;height:25px" class="mb-3">
                                    <span class="float-right">忘记密码?</span>
                                </div>
                                <div class="thirdparty-title mb-2" style="">
                                    <span>—————</span>
                                    <span>使用第三方账号登录</span>
                                    <span>—————</span>
                                </div>
                                <div class="thirdparty-box">
                                    <a class="box-one">
                                    <img src="/img/login-wechat.png" alt="">
                                    <span>企业微信</span></a>
                                </div>
                            </TabPane>
                            <TabPane label="二维码扫描" name="name3">
                                <div class="qrcode h-100 d-flex align-items-center justify-content-center" >
                                    <qrcode value="http://47.111.233.60" :options="{ width: 300 }"></qrcode>
                                </div>
                            </TabPane>
                        </Tabs>
                    </div>
                </div>
            </div>
            <!-- <div class="main" v-else>
                <a href="/downloads/apk/ESchool.apk" style="font-size: 24px;color: #2d8cf0!important;">Android 下载 </a>
            </div> -->
        </div>

        <Modal
        v-model="setLiveLectureTitleModal"
        class-name="set-live-lecture-title-modal"
        :mask-closable="false"
        @on-ok="addStreamModal"
        @on-cancel="formatTitle"
        :styles="{top:'140px',left:'42px'}"
        >
        <div class="container text-center">
            <h4 class="mb-2">Lecture title</h4>
            <Input v-model="lectureTitle" placeholder="输入演讲题目。" style="width: 300px" />
        </div>
        </Modal>

        <Modal
        footer-hide	
        v-model="showVideoModal"
        :styles="{top:'140px',left:'-244px'}"
        class-name="live-streaming-modal"
        :mask-closable="false"
        @on-cancel="endLecture"
        >   
            <div class="recording-btn-area">
                <!-- <ButtonGroup><Icon type="ios-cloud-upload" />
                    <Button type="primary" icon="ios-play"></Button>
                    <Button type="primary" icon="ios-pause"></Button>
                    <Button type="primary" icon="md-square"></Button>
                    <Button type="primary" icon="ios-cloud-upload"></Button>
                </ButtonGroup> -->

                <Button class="btnclass" @click="recordStart" :disabled="isRecord">{{isRecord ? 'Recording...': 'Record'}}</Button>
                <Button class="btnclass" @click="recordPause" :disabled="isPause">暂停</Button>
                <Button class="btnclass" @click="recordStop" :disabled="isStop">停止</Button>
                <Button class="btnclass" @click="recordSave" :disabled="isSave">保存    </Button>
            </div>
            <div id="meeting"></div>
        </Modal>

        <Modal
            v-model="chatModal"
            title="消息"
            class-name="chat-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            footer-hide
        >
            <chatComponent></chatComponent>
        </Modal>
        <Modal
            v-model="viewLiveLectureModal"
            title="视频讲座"
            class-name="liveLecture-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            footer-hide
        >
            <lectureComponent></lectureComponent>
        </Modal>
        <modal
            v-show="profileModal"
            @close="closeProfileModalModal"
        >
            <template v-slot:title>
                <p v-if="profileModalTitle == null">我的信息</p>
                <p v-else>{{profileModalTitle}}</p>
            </template> 
          
            <template v-slot:body>
               <profile
               @updateProfileMenu="updateProfileMenu"
               ></profile>
            </template> 
        </modal>
        <Modal
            v-model="viewBaiduMap"
            title="baidu"
            class-name="baidumap-modal"
            scrollable
            :mask-closable="false"
            footer-hide
            :styles="{top:'50px',left:'-435px'}"
        >
            <Baidumap></Baidumap>
        </Modal>
    </div>

</template>
<script>
import profile from './profile/profile'
import modal from './modal'
import fab from 'vue-fab'
import chatComponent from './pages/chatComponent'
import lectureComponent from './pages/lectureComponent'
import Avatar from 'vue-avatar'
import Baidumap from './pages/baidumap.vue'
export default {
    props:['user','permission'],
    components:{
        fab,
        chatComponent,
        modal,
        profile,
        lectureComponent,
        Avatar,
        Baidumap,
    },
    data(){
        return{
            isLoggedin:false,
            logoutModal:false,
            // loginView:false,
            positionTopLeft:'top-left',
            positionBottomRight:'bottom-right',
            bgColor: '#2d8cf0',
            bottomRight:'bottom-right',
            topLeft:"top-left",
            fabActions: [
                {
                    name: 'chat',
                    icon: 'chat'
                },
                {
                    name: 'map',
                    icon: 'location_on'
                },
                {
                    name: 'liveLecture',
                    icon: 'videocam'
                }
            ],
            data : {
                phoneNumber : '', 
                password: ''
            }, 
            isLogging: false,
            policy:true,
            chatModal:false, 

            //profile
            profileModal : false,

            //liveLecture
            showVideoModal:false,
            domain : "118.31.71.41",
            videoOptions:{
                roomName: "testLecture",
                // width: 1010,
                height: 610,
                parentNode: undefined,
                // configOverwrite: {
                // },
                // interfaceConfigOverwrite: {
                //     filmStripOnly: true
                // },
            },
            setLiveLectureTitleModal:false,
            viewLiveLectureModal:false,
            lectureTitle : "",
            LiveMeeting:{},
            recordingData: [],
            recorder:'',
            recorderStream:'',
            isRecord: false,
            isPause: true,
            isStop: true,
            isSave: true,
            isAdding:false,
            register:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:5
            },
            schoolList:[],
            profileModalTitle : null,
            viewBaiduMap:false,
        }
    },
    async created(){
        
        this.$store.commit('setUpdateUser',this.user);
        this.$store.commit('setUserPermission',this.permission);
    },
    methods:{
        updateProfileMenu(val){
            this.profileModalTitle = val;
        },
        chat(){
            this.chatModal = true;
        },
        map(){
            // this.$router.push('/baidumap')
            this.viewBaiduMap = true
        },
        liveLecture(){
            this.viewLiveLectureModal = true;
        },
        clickMenu(item){
            this.$router.push({path:item})
        },
        async login(){
            if(this.data.phoneNumber.trim()=='') return this.error('电话号码为必填项。')
            if(this.data.password.trim()=='') return this.error('密码是必需的。')
            if(this.data.password.length < 6) return this.error('错误的登录详细信息。')
            this.isLogging = true
            const res = await this.callApi('post', '/api/login', this.data)
            if(res.status===200){
                if(res.data.msg == undefined){
                    this.info('您的帐户未被允许。')
                }else{
                    this.success('操作成功')
                    if(!this.$isMobile()){
                        window.location = '/'
                    }else{
                        window.location = '/mobile'
                    }
                }
            }else{
                this.error('错误的登录详细信息')
                // if(res.status===401){
                //     this.info(res.data.msg)
                // }else if(res.status==422){
                //     for(let i in res.data.errors){
                //         this.error(res.data.errors[i][0])
                //     }
                // }
                // else{
                //     this.swr()
                // }
            }
            this.isLogging = false
        },
        showProfileModal(){
            this.profileModal = true;
        },
        closeProfileModalModal(){
            this.profileModal = false;
        },

        //liveLecture Methods
        async addStreamModal(){
            if(this.lectureTitle == ""){
                this.$Message.info('请写标题。');
                return;
            }
            this.videoOptions.roomName = this.lectureTitle;
            this.lectureTitle = "";
            this.showVideoModal = true;
            this.videoOptions.parentNode = document.querySelector('#meeting');
            this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        },
        endLecture(){
            this.LiveMeeting.dispose();
        },
        formatTitle(){
            this.lectureTitle = "";
        },
        async recordStart(){
            let gumStream, gdmStream;
            // try {
            gumStream = await navigator.mediaDevices.getUserMedia({video: false, audio: true});
            gdmStream = await navigator.mediaDevices.getDisplayMedia({video: {displaySurface: "browser"}, audio: true});

            
            this.recorderStream = gumStream ? this.mixer(gumStream, gdmStream) : gdmStream;
            this.recorder = new MediaRecorder(this.recorderStream, {mimeType: 'video/webm'});

            this.recorder.ondataavailable = e => {

                if (e.data && e.data.size > 0) {
                    this.recordingData.push(e.data);
                }
            };

            this.recorder.onStop = () => {
                this.recorderStream.getTracks().forEach(track => track.stop());
                gumStream.getTracks().forEach(track => track.stop());
                gdmStream.getTracks().forEach(track => track.stop());
            };

            this.recorderStream.addEventListener('inactive', () => {
                stopCapture();
            });

            this.recorder.start();
            
            this.isRecord = true;
            this.isPause = false;
            this.isStop = false;
            this.isSave = true;

        },

        recordPause(){
            if(this.recorder.state ==='paused'){
                this.recorder.resume();
            }
            else if (this.recorder.state === 'recording'){
                this.recorder.pause();
            }
        },

        recordStop(){
            this.recorder.stop();
            
            this.isRecord = false;
            this.isPause = true;
            this.isStop = true;
            this.isSave = false;
        },
        goHome(){
            this.$router.push({path:'/'})
        },
        recordSave(){
            const blob = new Blob(this.recordingData, {type: 'video/webm'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `${this.getFilename()}.webm`;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 100);
        },


        /**
         * Returns a filename based ono the Jitsi room name in the URL and timestamp
         * */
        getFilename(){
            const now = new Date();
            const timestamp = now.toISOString();
            const room = new RegExp(/(^.+)\s\|/).exec(document.title);
            if(room && room[1]!=="")
                return `${room[1]}_${timestamp}`;
            else
                return `recording_${timestamp}`;
        },
        mixer(stream1, stream2){
            const ctx = new AudioContext();
            const dest = ctx.createMediaStreamDestination();

            if(stream1.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream1).connect(dest);

            if(stream2.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream2).connect(dest);

            let tracks = dest.stream.getTracks();
            tracks = tracks.concat(stream1.getVideoTracks()).concat(stream2.getVideoTracks());

            return new MediaStream(tracks)
        },
        async userRegister(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/users',this.register)
            if(res.status === 200){
                this.success('已成功添加管理员用户，但不允许！');
                this.register.name = '';
                this.register.phoneNumber = '';
                this.register.password = '';
                this.register.roleId = null;
                 window.location = '/#/schoolSpace/index'

            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
                }else{
                    this.swr()
                }
               
            }
            this.isAdding = false;
        }
    }
}
</script>

<style>

    .logout-modal {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logout-modal .ivu-modal-content{
        height: unset!important;
        width: unset!important;
    }
    .logout-modal .ivu-modal {
        top: 0;
    }

     .thirdparty-box{
        display: flex;
        justify-content: space-around;
    }
    .box-one{
        display: flex;
        align-items: center;
    }
    .thirdparty-box .box-one img{
        display: inline-block;
        width: 22px;
        height: 22px;
        margin-right: 4px;
    }
    .thirdparty-box .box-one span{
        height: 22px;
        color: #999;
        font-size: 14px;
    }
    #top-left-wrapper{
        position: unset!important;
            padding: 10px!important;
    }
    #bottom-right-wrapper{
        right: 18vw!important;
        bottom:8vh!important;
    }
</style>