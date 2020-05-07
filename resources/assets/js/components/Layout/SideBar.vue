<template>
  <div id="aside" class="app-aside fade nav-dropdown grey">
    <!-- fluid app aside -->
    <div class="navside dk" data-layout="column">
      <div class="navbar no-radius">
        <!-- brand -->
        <a href="#" class="navbar-brand">
        	<img :src="'./../../../images/logo-white.png'" alt="logo" />
        </a>
        <!-- / brand -->
      </div>
      <div data-flex class="hide-scroll">
          <nav class="scroll nav-light">
            <ul class="nav" data-ui-nav>
              <li class="nav-header hidden-folded">
                <span class="text-xs">Main</span>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/dashboard">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe3fc;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m1}} </span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/members">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe7fb;</i>
                  </span>
                  <span class="nav-text">{{menuItems.m2}}</span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/zones">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe7f1;</i>
                  </span>
                  <span class="nav-text"> Zones </span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/homecells">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe88a;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m3}} </span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/upcoming-activities">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe878;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m4}} </span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/users">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe7fb;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m5}} </span>
                </router-link>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/departments_and_ministries">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe8a4;</i>
                  </span>
                  <span class="nav-text"> Setting </span>
                </router-link>
              </li>

              <li class="nav-header hidden-folded m-t">
                <span class="text-xs">Accounting</span>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/accounts">
                  <span class="nav-icon text-white no-fade">
                    <i class="fa fa-briefcase"></i>
                  </span>
                  <span class="nav-text"> {{menuItems.m6}} </span>
                </router-link>
              </li>
              <li>
                <a>
                  <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                  </span>
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe8f6;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m7}} </span>
                </a>
                <ul class="nav-sub">
                  <li>
                    <router-link class="nav-link side-nav-close" to ="/sunday-basket">
                      <span class="nav-text"> {{menuItems.m8}} </span>
                    </router-link>
                  </li>
                  <li>
                    <router-link class="nav-link side-nav-close" to ="/tithes">
                      <span class="nav-text"> {{menuItems.m9}} </span>
                    </router-link>
                  </li>
                  <li>
                    <router-link class="nav-link side-nav-close" to ="/other-offerings">
                      <span class="nav-text"> {{menuItems.m10}} </span>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li>
                <router-link class="nav-link side-nav-close" to ="/transactions">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe8b0;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m11}} </span>
                </router-link>
              </li>

              <li class="nav-header hidden-folded m-t">
                <span class="text-xs">Reports</span>
              </li>
              <li>
                <router-link class="nav-link side-nav-close" to ="/reports">
                  <span class="nav-icon text-white no-fade">
                    <i class="material-icons">&#xe873;</i>
                  </span>
                  <span class="nav-text"> {{menuItems.m12}} </span>
                </router-link>
              </li>


            </ul>
          </nav>
      </div>

      <div data-flex-no-shrink="">
        <div class="nav-fold dropup">
            <div class="clear hidden-folded p-x">
                <span class="block _500 text-muted"> {{languageTitle}} </span>
                <select class="form-control form-control-sm bg white" @change="changeLanguage" v-model="language">
                    <option v-for="(language, index) in languages" :key="index" :value="language">
                        {{language}} </option>
                </select>
            </div>
        </div>
      </div>

    </div>
  </div>
</template>
<script>
import store from '../../store';
import {Language} from './../MockData/Language';
import {mapState, mapActions} from 'vuex';

export default {
    data:function(){
        return{
            languageTitle: '',
            language: '',
        }
    },
    created(){

    },
    mounted(){
        var language = this.$store.getters.language;
        this.language = language;
    },
    computed:{
        ...mapState(['languageData']),
        languages(){ return this.$store.getters.languages },
        menuItems(){
            return this.$store.getters.languageData.menu;
        }
    },
    methods:{
        ...mapActions(['LANGUAGE']),
        changeLanguage(){
            const language = this.language;
            this.$store.dispatch('LANGUAGE', language);
        }
    }
};
</script>

