<template>
	<div>
		<button v-if="!isAuthenticated" @click="authenticate">Log in with Spotify</button>
		<div v-if="isAuthenticated">
			<h1>Hello, {{ user.display_name }}</h1>
			<button @click="logout">Log out</button>
		</div>
	</div>
</template>

<script lang="ts">
	import { defineComponent } from 'vue';
	import { SpotifyClient } from 'aerni/spotify';

	export default defineComponent({
		components: {},
		data() {
			return {
				spotifyClient: new SpotifyClient(),
				isAuthenticated: false,
				user: {},
			};
		},
		methods: {
			authenticate() {
				// Відправити користувача на URL-адресу Spotify для авторизації.
				window.location.href = this.spotifyClient.getAuthorizationUrl();
			},
			getUser() {
				// Отримати інформацію про користувача, якщо він авторизований.
				if (this.isAuthenticated) {
					const response = await this.spotifyClient.get('https://api.spotify.com/v1/me');
					this.user = await response.json();
				}
			},
			logout() {
				// Вийти з Spotify.
				this.spotifyClient.logout();

				// Змінити текст кнопки на "Log in with Spotify".
				this.buttonText = "Log in with Spotify";
			},
		},
		mounted() {
			// Відстежувати зміну стану авторизації.
			this.spotifyClient.onAuthStateChange((isAuthenticated) => {
				this.isAuthenticated = isAuthenticated;
				if (this.isAuthenticated) {
					this.getUser();
					this.buttonText = this.user.display_name;
				} else {
					this.buttonText = "Log in with Spotify";
				}
			});
		},
	});
</script>

<style lang="less" scoped>
	.header {
		width: 992px;
		margin: 0 auto;
		height: 100px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>
