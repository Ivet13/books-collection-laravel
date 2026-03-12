      <x-admin.admin-layout>

          {{-- CSRF para fetch --}}
          <meta name="csrf-token" content="{{ csrf_token() }}">

          <x-slot:table>
              <x-admin.publishers.list :records="$records" />
          </x-slot:table>

          <x-slot:form>
              <x-admin.publishers.form :records="$records" />
          </x-slot:form>

      </x-admin.admin-layout>
