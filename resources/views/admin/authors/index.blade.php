      <x-admin.admin-layout>

          {{-- CSRF para fetch --}}
          <meta name="csrf-token" content="{{ csrf_token() }}">

          <x-slot:form>
              <x-admin.author-form />
          </x-slot:form>

          <x-slot:table>

              @include('admin.authors._list_and_pagination', ['records' => $records])
          </x-slot:table>

      </x-admin.admin-layout>
