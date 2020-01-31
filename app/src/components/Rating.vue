<script>
import { VRating } from 'vuetify/lib'
export default {
    extends: VRating,
    name: "vc-rating",
    methods: {
        onMouseEnter (event, index){
            this.runDelay('open', () => {
                this.hoverIndex = this.genHoverIndex(event, index)
                this.$emit('onMouseEnterIcon', this.hoverIndex);
            })
        },
        onMouseLeave (){
            this.runDelay('close', () => {
                this.hoverIndex = -1;
                this.$emit('onMouseLeaveIcon', this.hoverIndex);
            })
        }
    }
};
</script>