<x-app-layout>
    <x-proclubs.breadcrumbs :breadcrumbs="$breadcrumbs" />

    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
        <x-proclubs.results :results="$results" :streaks="$streaks" :myClubId="$myClubId" />
    </div>

    <script>
        function contactForm(matchId) {
          return {
            formData: {
              youtubeURL: '',
              matchId: matchId
            },
            message: '',
            loading: false,
            buttonLabel: 'Submit',

            submitData() {
              this.buttonLabel = 'Submitting...'
              this.loading = true;
              this.message = ''

              fetch('highlights', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _token:  "{{ csrf_token() }}",
                    formData: this.formData
                })
              })
                .then(() => {
                  this.message = 'Form sucessfully submitted!'
                  this.formData.youtubeURL = '';
                })
                .catch(() => {
                  this.message = 'Ooops! Something went wrong!'
                })
                .finally(() => {
                  this.loading = false;
                  this.buttonLabel = 'Submit'
                  this.formData.youtubeURL = '';
                })
            }
          }
        }

      </script>
</x-app-layout>
