<template>
  <div>
    <v-container v-if="images.length" :class="{ 'is-open': isOpen }" class="v-image-gallery">
      <v-row>
        <v-col v-for="(image, index) in images" :key="index" class="d-flex child-flex" cols="4">
          <a @click.prevent="openImageGallery(index)" @keyup.enter="openImageGallery(index)" href="javascript:void(0)">
            <v-img :src="image.small" :lazy-src="image.small" :alt="image.name" aspect-ratio="1" class="grey lighten-2">
              <template #placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="primary" />
                </v-row>
              </template>
            </v-img>
          </a>
        </v-col>
      </v-row>
    </v-container>
    <v-dialog v-model="imageGallery" class="v-image-gallery__dialog">
      <v-carousel v-model="imageGalleryIndex" hide-delimiters class="v-image-gallery__carousel">
        <v-carousel-item v-for="(image, key) in images" :key="key" class="v-image-gallery__item">
          <div>
            <v-img :src="image.small" :alt="image.name" eager contain class="v-image-gallery__image" />
            <a :href="image.url" class="v-image-gallery__download" download>
              <v-icon color="primary" small left>
                mdi mdi-download
              </v-icon>
              Download
            </a>
          </div>
        </v-carousel-item>
      </v-carousel>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      required: true
    }
  },
  data: () => ({
    imageGallery: false,
    imageGalleryIndex: 0,
    isOpen: false
  }),
  methods: {
    openImageGallery(key) {
      this.isOpen = false;
      this.imageGalleryIndex = key;
      this.imageGallery = true;
      this.isOpen = true;
    }
  }
};
</script>
