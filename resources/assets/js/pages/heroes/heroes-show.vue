<style lang="scss">
    @import './heroes-show';
</style>

<template>
    <div id="page">
        <main-header>
            <banner :parameters="topBannerParameters"
                    id="top-banner"
                    class="banner--slim"
            >
                <div>
                    <h1>{{ state.name || 'New Record' }}</h1>
                    <h3>{{ state.clan_name || '' }}</h3>
                    <h6>{{ state.region | region}}</h6>
                </div>
            </banner>
        </main-header>

        <main-content>
            <h2 class="section-header">
                Hero
            </h2>
            <section class="hero-section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="block">
                                <div class="block__body">
                                    <img :src="state.class | classCrest"
                                         alt="portrait"
                                         class="img-fluid img-center"
                                    >
                                    <a :href="state | battlenet"
                                       class="battlenet-link"
                                       target="_blank"
                                    >
                                        Battle.net
                                    </a>
                                    <p>
                                        <small>Update Available: {{ state.available_in || 'Now' }}</small>
                                    </p>
                                    <button class="btn btn--secondary btn--icon btn-lg"
                                            @click="updateHero"
                                            :disabled="! state.queueable"
                                    >
                                        Update <i class="fa fa-refresh"></i>
                                    </button>
                                    <bounce v-if="loadingAnimation"
                                            transition="fade"
                                            class="animated"
                                    ></bounce>
                                    <div class="back-to-profile">
                                        <a href="/profiles/{{ this.state.profile_id }}"
                                           class="text--tertiary"
                                        >
                                            <i class="fa fa-angle-double-left"></i> Back to Profile
                                        </a>
                                    </div>
                                </div>
                                <div class="block__body block__body--flush"
                                     v-if="state.season_rankings.length > 0"
                                >
                                    <div class="block__row">
                                        <h5 class="block__header">Greater rift</h5>
                                        <ul class="list">
                                            <li class="list__item list__item--link"
                                                v-for="ranking in rankings"
                                                href="/leaderboards/{{ ranking.id }}/show"
                                            >
                                                <span class="flex-50">{{ ranking.players == 1 ? 'Solo' : ranking.players + ' Players' }}</span>
                                                <span class="flex-50">{{ ranking.rift_level }}</span>
                                                <span class="list__item--link__arrow">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="block__body">
                                    <div class="block__row">
                                        <h5 class="block__header">
                                            {{ state.season ? 'Season' : 'Era' }}
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 block__col">
                                                <h3 class="text--tertiary block__col__header">
                                                    {{ state.paragon_level | number }}
                                                </h3>
                                                <small>paragon</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xs-12 text-xs-center p-t-3 p-b-3"
                             v-if="!state.queued_at"
                        >
                            <h1>
                                <i class="fa fa-exclamation-triangle"></i>
                                New Hero Record
                            </h1>
                            <h6>To refresh this hero's profile, click update.</h6>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 animated"
                             v-if="state.items.length > 0"
                             transition="fade"
                        >
                            <div class="row" style="justify-content: center;">
                                <div class="col-md-4 col-sm-12 col-xs-12"
                                     v-for="item in state.items"
                                     style="margin-bottom: .5rem"
                                >
                                    <a href="#"
                                       class="block block--gear block--{{ item.display_color }}"
                                       data-d3tooltip="{{ item.pivot.tool_tip_params }}"
                                    >
                                        <div class="block__body">
                                            <img :src="item.icon | powerIcon" alt="" class="block--gear__img">
                                            <p class="block--gear__text">
                                                {{ item.name }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 animated"
                             v-if="state.skills.length > 0 || state.powers.length > 0"
                             transition="fade"
                        >
                            <div class="block">
                                <div class="block__body block__body--flush">
                                    <div class="block__row"
                                         v-if="state.skills.length > 0"
                                    >
                                        <h5 class="block__header">Active</h5>
                                        <ul class="list">
                                            <a class="list__item list__item--link list__item--link--power"
                                               v-for="skill in state.skills | skillType 'active'"
                                               :href="skill | activeSkillLink"
                                            >
                                                <span class="flex-70 text-xs-left">
                                                    <div>{{ skill.name }}</div>
                                                    <small class="text--secondary">{{ skill.rune }}</small>
                                                </span>
                                                <span class="flex-30 text-xs-right">
                                                    <img :src="skill.icon | skillIcon" alt="">
                                                </span>
                                            </a>
                                        </ul>
                                    </div>
                                    <div class="block__row"
                                         v-if="state.skills.length > 0"
                                    >
                                        <h5 class="block__header">Passive</h5>
                                        <ul class="list">
                                            <a class="list__item list__item--link list__item--link--power"
                                               v-for="skill in state.skills | skillType 'passive'"
                                               :href="skill | passiveSkillLink"
                                            >
                                                <span class="flex-70 text-xs-left">
                                                    <div>{{ skill.name }}</div>
                                                </span>
                                                <span class="flex-30 text-xs-right">
                                                    <img :src="skill.icon | skillIcon" alt="">
                                                </span>
                                            </a>
                                        </ul>
                                    </div>
                                    <div class="block__row"
                                         v-if="state.powers.length > 0"
                                    >
                                        <h5 class="block__header">Powers</h5>
                                        <ul class="list">
                                            <a class="list__item list__item--link list__item--link--power"
                                               v-for="power in state.powers"
                                               data-d3tooltip="{{ power.tool_tip_params }}"
                                            >
                                                <span class="flex-70 text-xs-left">
                                                    {{ power.name }}
                                                </span>
                                                <span class="flex-30 text-xs-right">
                                                    <img :src="power.icon | powerIcon" alt="">
                                                </span>
                                            </a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main-content>
    </div>
</template>

<script type="text/babel">
    import heroStub from '../../stubs/hero';

    export default {
        data () {
            return {
                state: heroStub,
                loadingAnimation: false
            }
        },

        props: ['data'],

        computed: {
            topBannerParameters () {
                return {
                    background: `url("/img/${this.state.class}/banner.jpg") no-repeat fixed 50% 0`,
                }
            },

            rankings () {
                let players = []
                this.state.season_rankings.forEach(i => {
                    if (!players.find(j => j.players === i.players)) {
                        players.push(i)
                    }
                })

                return players
            }
        },

        filters: {
            activeSkillLink (skill) {
                return `http://us.battle.net/d3/en/class/${this.state.class}/active/${skill.slug}?runeType=${skill.rune_type}`
            },

            passiveSkillLink (skill) {
                return `http://us.battle.net/d3/en/class/${this.state.class}/passive/${skill.slug}`
            },

            skillType (obj, type) {
                if (typeof obj !== 'undefined') {
                    return obj.filter($i => {
                        if ($i.type === type) {
                            return $i;
                        }
                    });
                }
            },

            skillIcon (icon) {
                return `http://media.blizzard.com/d3/icons/skills/21/${icon}.png`
            },

            powerIcon (icon) {
                return `http://media.blizzard.com/d3/icons/items/small/${icon}.png`
            },

            battlenet (state) {
                return `https://${state.region}.battle.net/d3/profile/${state.profile.battle_tag.replace('#', '-')}/hero/${state.battlenet_hero_id}`
            }
        },

        ready () {
            this.init()
        },

        methods: {
            init () {
                this.state = JSON.parse(this.data)

                if (this.state.stats === null) {
                    this.$root.message('warning', 'New Hero Record')
                }
            },

            updateHero () {
                this.loadingAnimation = true
                this.state.queueable = false

                this.$root.message('info', 'Hero is currently in queue.')

                this.$http.patch(`/api/heroes/${this.state.id}`).then(response => {
                    this.state = response.data
                    this.loadingAnimation = false
                    this.$root.message('success', 'Hero updated', 4000)
                }).catch(response => {
                    this.loadingAnimation = false
                    this.$root.message('warning', 'Hero record not found.  This could be due to an error processing the request, or the Hero was banned. Please try again later');
                })
            }
        }
    }
</script>