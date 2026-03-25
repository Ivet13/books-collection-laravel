      <x-admin.admin-layout>

          {{-- CSRF para fetch --}}
          <meta name="csrf-token" content="{{ csrf_token() }}">

          <x-slot:table>
              <x-admin.faqs.list :records="$records" />
          </x-slot:table>

          <x-slot:form>
              <x-admin.faqs.form :records="$records" />
          </x-slot:form>

      </x-admin.admin-layout>
