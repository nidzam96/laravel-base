<template>
	<div class="card w-100 h-100" style="position: relative;">
		<div v-if="false" class="map-overlay"></div>
		<div class="card-body p-1">
			
			<l-map
				v-if="map_location"
	            :zoom="zoom"
	            :center="[parseFloat(map_location.latitude), parseFloat(map_location.longitude)]"
	            @update:zoom="zoom_updated"
	            @update:center="center_updated"
	            @update:bounds="bounds_updated"
	            style="height: 100%;"
	            ref="map"
	        >
	            <l-tile-layer :url="url"></l-tile-layer>
	            <l-marker 
	            	v-if="map_location" 
	            	:lat-lng="[parseFloat(map_location.latitude), parseFloat(map_location.longitude)]"
	            	:draggable="!disabled && marker_draggable"
        			@update:lat-lng="on_marker_update"
	            ></l-marker>
	        </l-map>
	    </div>
	    <div class="card-footer p-1">
	        <div class="row">
	        	<div class="col-12 col-md-6 pr-1">
	        		<div class="input-group">
						<div class="input-group-prepend">
					    	<span class="input-group-text text-muted bg-white">Latitude</span>
					  	</div>
					  	<input 
                type="number" 
                class="form-control" 
                placeholder="Latitude"  
                v-model="latitude" 
                :disabled="disabled"
                v-on:input="$v.latitude.$touch"
                :class="{
                        'is-invalid': $v.latitude.$error, 
                        'is-valid': $v.latitude.$dirty && !$v.latitude.$error
                    }"
              >
					</div>
	        	</div>
	        	<div class="col-12 col-md-6 pl-1">
	        		<div class="input-group">
						<div class="input-group-prepend">
					    	<span class="input-group-text text-muted bg-white">Longitude</span>
					  	</div>
					  	<input 
                type="number"
                class="form-control" 
                placeholder="Longitude" 
                v-model="longitude" 
                :disabled="disabled"
                v-on:input="$v.longitude.$touch"
                :class="{
                        'is-invalid': $v.longitude.$error, 
                        'is-valid': $v.longitude.$dirty && !$v.longitude.$error
                    }"
              >
					</div>
	        	</div>
	        </div>
	    </div>
    </div>
</template>

<script>

import { required, requiredIf, minLength } from 'vuelidate/lib/validators';

export default {

	props: [
      'options', 
      'value', 
      'height', 
      'width',
      'disabled'
    ],

    data() {
  		return {
  			'url' 				: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
  			'default_location'	: { // GPS for Kuala Lumpur
  				'latitude' 	: '3.137376',
  				'longitude' : '101.683443'
  			},
  			'map_location'	: null,
  			'zoom' 			: 10,
  			'bounds' 		: null,
  			'marker_draggable' : true,
  			'timeout'		: null,
  			'latitude'		: null,
  			'longitude'		: null
  		};
  	},

    validations() {

      let validations = {
        'latitude': {
          required
        },
        'longitude': {
          required
        }
      };

      return validations;

    },

    mounted() {
        
        if (typeof this.value !== 'undefined') {
        	this.map_location = this.value;
        }
        else {
        	this.map_location = this.default_location;
        }

        this.latitude = this.map_location.latitude;
        this.longitude = this.map_location.longitude;

        if (this.disabled) {
        	this.marker_draggable = !this.disabled;
        }

    },

    computed: {

    },

    watch: {

    	map_location: {
    		handler(value) {
    			//console.log(value);

          if (!this.$v.latitude.$invalid && !this.$v.longitude.$invalid) {
      			let data = {
      				'latitude'  : this.map_location.latitude,
      				'longitude' : this.map_location.longitude
      			};
      			this.$emit('input', data);
          }
          else {
            let data = {
              'latitude'  : null,
              'longitude' : null
            };
            this.$emit('input', data);
          }
    		},
    		deep: true
    	},

    	latitude(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
          if (!isNaN(value)) {
            this.map_location.latitude = parseFloat(value);
          }
          else {
            this.map_location.latitude = null;
          }
    		}, 1000);
    	},
    	

    	longitude(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
          if (!isNaN(value)) {
            this.map_location.longitude = parseFloat(value);
          }
          else {
            this.map_location.longitude = null;
          }
    		}, 1000);
    	}
    },

    methods: {
   		zoom_updated (zoom) {
        	this.zoom = zoom;
      	},

      	center_updated (center) {
        	this.center = center;
      	},

      	bounds_updated (bounds) {
        	this.bounds = bounds;
      	},

      	set_map_location(latitude, longitude) {
      		this.map_location.latitude 	= latitude;
      		this.map_location.longitude = longitude;
      		this.latitude = latitude;
      		this.longitude = longitude;
      	},

      	on_marker_update(event) {
      		//console.log(event);
      		clearTimeout(this.timeout);
      		this.timeout = setTimeout(() => {
      			this.set_map_location(event.lat, event.lng);
      		}, 1000);
      	}

      	

    }
}
</script>

<style scoped>
.map-overlay {
	position: absolute;
	height: 100%;
	width: 100%;
	background-color: rgba(0,0,0,0);
	z-index: 100000000;
}
</style>
