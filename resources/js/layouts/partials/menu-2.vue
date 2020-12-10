<template>
  <v-list dense>
    <v-list-item
      to="/dashboard"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="deep-purple lighten-5"
      >
        <i class="fas fa-home fa-fw deep-purple--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('general.dashboard') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      to="/configuration/basic"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="red lighten-5"
      >
        <i class="fas fa-cog fa-fw red--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.basic_configuration') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/social"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="amber lighten-5"
      >
        <i class="fas fa-share-alt fa-fw amber--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.social_network') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/system"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="indigo lighten-5"
      >
        <i class="fas fa-cogs fa-fw indigo--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.system_configuration') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/mail"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="brown lighten-5"
      >
        <i class="fas fa-envelope fa-fw brown--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.mail_configuration') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration') && getConfig('multilingual')"
      to="/configuration/locale"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="orange lighten-5"
      >
        <i class="fas fa-globe fa-fw orange--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.locale') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration') && hasRole('admin')"
      to="/configuration/role"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="deep-purple lighten-5"
      >
        <i class="fas fa-users fa-fw deep-purple--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.role') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration') && hasRole('admin')"
      to="/configuration/permission"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="pink lighten-5"
      >
        <i class="fas fa-key fa-fw pink--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.permission') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/sms"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="cyan lighten-5"
      >
        <i class="fas fa-comment fa-fw cyan--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.sms') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/payment/gateway"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="lime lighten-5"
      >
        <i class="fas fa-credit-card fa-fw lime--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('finance.payment_gateway') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/authentication"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="blue lighten-5"
      >
        <i class="fas fa-sign-in-alt fa-fw blue--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('auth.authentication') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/menu"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="brown lighten-5"
      >
        <i class="fas fa-ellipsis-h fa-fw brown--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.menu') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-item
      v-show="configMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/module"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="cyan lighten-5"
      >
        <i class="fas fa-boxes fa-fw cyan--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.module_configuration') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="indigo lighten-5"
        >
          <i class="fas fa-user-circle fa-fw indigo--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.reception_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/reception/enquiry/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.enquiry_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/reception/enquiry/source"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.enquiry_source') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/reception/visiting/purpose"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.visiting_purpose') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/reception/calling/purpose"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.calling_purpose') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/reception/complaint/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.complaint_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="blue lighten-5"
        >
          <i class="fas fa-school fa-fw blue--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.academic_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/academic/course/group"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.course_group') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/academic/institute"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.institute_other') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/academic/certificate/template"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.certificate_template') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/academic/id-card/template"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.id_card_template') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="deep-purple lighten-5"
        >
          <i class="fas fa-money-bill-alt fa-fw deep-purple--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.finance_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/finance/transaction/category"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.transaction_category') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/finance/payment/method"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.payment_method') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="green lighten-5"
        >
          <i class="fas fa-graduation-cap fa-fw green--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.student_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/student"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/student/group"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.student_group') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/student/document/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.document_type_only') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="blue-grey lighten-5"
        >
          <i class="fas fa-gem fa-fw blue-grey--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('behaviour.behaviour_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/behaviour/skill"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('behaviour.skill') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/behaviour/skill/employee"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('behaviour.employee_skill') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="purple lighten-5"
        >
          <i class="fas fa-file-alt fa-fw purple--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/exam/term"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.term') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/exam/assessment"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.assessment') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/exam/observation"
        exact
      >
        <v-list-item-title>{{ trans('exam.observation') }}</v-list-item-title>
      </v-list-item>
      <v-list-item
        to="/configuration/exam/grade"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.grade') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="pink lighten-5"
        >
          <i class="fas fa-user-tie fa-fw pink--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.employee_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/employee"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/category"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.category') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/designation"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.designation') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/department"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.department') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/group"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.employee_group') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/document/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.document_type_only') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/leave/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.leave_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/attendance/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.attendance_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/pay/head"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.pay_head') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/asset/category"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee_asset.asset_category') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/employee/asset/item"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee_asset.asset_item') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="cyan lighten-5"
        >
          <i class="fas fa-truck fa-fw cyan--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.transport_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/transport/vehicle/document/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.vehicle_document_type_only') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/transport/vehicle/fuel/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.vehicle_fuel_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/transport/vehicle/service/center"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.vehicle_service_center_only') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="amber lighten-5"
        >
          <i class="fas fa-book fa-fw amber--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.library_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/library"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/library/book/author"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book_author') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/library/book/language"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book_language') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/library/book/topic"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book_topic') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/library/book/publisher"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book_publisher') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/library/book/condition"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book_condition') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="yellow lighten-5"
        >
          <i class="fas fa-calendar-alt fa-fw yellow--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.calendar_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/calendar/event/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.event_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="purple lighten-5"
        >
          <i class="fas fa-newspaper fa-fw purple--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('post.post_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/post/article/type"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('post.article_type') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="blue-grey lighten-5"
        >
          <i class="fas fa-building fa-fw blue-grey--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('asset.asset_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/asset/building"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('asset.building') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/asset/room"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('asset.room') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="purple lighten-5"
        >
          <i class="fas fa-suitcase fa-fw purple--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.frontend_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/frontend/index"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.frontend') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-item
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      to="/configuration/custom-field"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="orange lighten-5"
      >
        <i class="fas fa-cubes fa-fw orange--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.custom_field') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-list-group
      v-show="moduleConfigMenu && hasPermission('access-configuration')"
      v-if="getConfig('made') === 'saudi'"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="yellow lighten-5"
        >
          <i class="fas fa-align-justify fa-fw yellow--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('misc.misc_configuration') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        to="/configuration/misc/religion"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('misc.religion') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/misc/caste"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('misc.caste') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/misc/category"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('misc.category') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        to="/configuration/misc/blood/group"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('misc.blood_group') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>

    <v-list-item
      v-show="moduleMenu && showMenu('institute_document') && hasPermission('list-institute-document')"
      to="/institute/document"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="teal lighten-5"
      >
        <i class="fas fa-file fa-fw teal--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('institute.document') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-list-group
      v-show="moduleMenu && showMenu('reception') && hasAnyPermission(['list-enquiry','list-visitor-log','list-postal-record','list-call-log','list-complaint','list-gate-pass'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="blue lighten-5"
        >
          <i class="fas fa-user-circle fa-fw blue--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.reception') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-enquiry') && showMenu('enquiry')"
        to="/reception/enquiry"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.admission_enquiry') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-visitor-log') && showMenu('visitor_log')"
        to="/reception/visitor/log"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.visitor_log') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-call-log') && showMenu('call_log')"
        to="/reception/call/log"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.call_log') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-postal-record') && showMenu('postal_record')"
        to="/reception/postal/record"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.postal_record') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-complaint') && showMenu('complaint')"
        to="/reception/complaint"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.complaint') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-gate-pass') && showMenu('gate_pass')"
        to="/reception/gate/pass"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.gate_pass') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-visitor-message') && showMenu('visitor_message')"
        to="/reception/visitor/message"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('reception.visitor_message') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('academic') && hasAnyPermission(['list-academic-session','list-course','list-batch','list-class-teacher','list-subject','list-subject-teacher','list-class-timing','list-timetable'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="pink lighten-5"
        >
          <i class="fas fa-school fa-fw pink--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.academic') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-academic-session') && showMenu('academic_session')"
        to="/academic/session"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.academic_session') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-course') && showMenu('course')"
        to="/academic/course"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.course') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-batch') && showMenu('batch')"
        to="/academic/batch"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.batch') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-class-teacher') && showMenu('class_teacher')"
        to="/academic/class/teacher"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.class_teacher') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-subject') && showMenu('subject')"
        to="/academic/subject"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.subject') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-subject-teacher') && showMenu('subject_teacher')"
        to="/academic/subject/teacher"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.subject_teacher') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-class-timing') && showMenu('class_timing')"
        to="/academic/class/timing"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.class_timing') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-timetable') && showMenu('timetable')"
        to="/academic/timetable"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.timetable') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-certificate') && showMenu('certificate')"
        to="/academic/certificate"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('academic.certificate') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('student') && hasAnyPermission(['list-registration','list-student','edit-roll-number','generate-student-id-card','list-student-attendance','promote-student','terminate-student'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="deep-orange lighten-5"
        >
          <i class="fas fa-graduation-cap fa-fw deep-orange--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.student') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('import-student') && showMenu('student_import')"
        to="/student/import"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.import') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-registration') && showMenu('registration')"
        :to="getConfig('made') === 'saudi' ? `/student/registration/card-view` : `/student/registration`"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.registration') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="(hasPermission('list-student') || hasPermission('list-class-teacher-wise-student')) && showMenu('student_list')"
        :to="getConfig('made') === 'saudi' ? `/student/card-view` : `/student/list`"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.admission') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <template v-if="getConfig('made') === 'saudi'">
        <v-list-item
          v-if="(hasPermission('list-student') || hasPermission('list-class-teacher-wise-student')) && showMenu('student_behaviour')"
          to="/student/behaviour"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('behaviour.behaviour') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
      <v-list-item
        v-if="hasPermission('edit-roll-number') && showMenu('roll_number')"
        to="/student/roll/number"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.roll_number') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('generate-student-id-card') && showMenu('student_id_card')"
        to="/student/id-card"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.id_card') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('edit-student') && showMenu('student_image_upload')"
        to="/student/image"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.image_upload') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-student-attendance') && showMenu('student_attendance')"
        to="/student/attendance"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.attendance') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('promote-student') && showMenu('promotion')"
        to="/student/promotion"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.promotion') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('terminate-student') && showMenu('termination')"
        to="/student/termination"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.termination') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('edit-student') && showMenu('student_parent')"
        to="/student/parent"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.parent') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('edit-student') && showMenu('student_login')"
        to="/student/login"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('student.login') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('employee') && hasAnyPermission(['list-employee', 'generate-employee-id-card'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="light-blue lighten-5"
        >
          <i class="fas fa-user-tie fa-fw light-blue--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.employee') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('import-employee') && showMenu('employee_import')"
        to="/employee/import"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.import') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('employee_list')"
        :to="getConfig('made') === 'saudi' ? `/employee/card-view` : `/employee/list`"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.employee_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <template v-if="getConfig('made') === 'saudi'">
        <v-list-item
          v-if="showMenu('employee_behaviour')"
          to="/employee/behaviour"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('behaviour.behaviour') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
      <v-list-item
        v-if="hasPermission('generate-employee-id-card') && showMenu('employee_id_card')"
        to="/employee/id-card"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.id_card') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('employee_attendance')"
        to="/employee/attendance"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.attendance') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('employee_leave')"
        to="/employee/leave"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.leave') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('employee_payroll')"
        to="/employee/payroll"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('employee.payroll') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('employee_asset')"
        to="/employee/asset/transfer"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('asset.asset') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('exam') && hasAnyPermission(['list-exam-schedule','list-exam-mark','access-exam-report','access-class-teacher-wise-exam-report','list-online-exam'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="indigo lighten-5"
        >
          <i class="fas fa-file-alt fa-fw indigo--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.exam') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-exam-schedule') && showMenu('exam_schedule')"
        to="/exam/schedule"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.schedule') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-exam-mark') && showMenu('exam_record_mark')"
        to="/exam/record"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.record') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasAnyPermission(['access-exam-report','access-class-teacher-wise-exam-report']) && showMenu('exam_report_card')"
        to="/exam/report"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.report') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <template v-if="hasNotAnyRole(['student','parent']) && showMenu('exam_topper_report')">
        <v-list-item
          v-if="hasAnyPermission(['access-exam-report','access-class-teacher-wise-exam-report'])"
          to="/exam/report/topper"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.topper_report') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
      <v-list-item
        v-if="hasPermission('list-online-exam') && showMenu('online_exam')"
        to="/online-exam"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('exam.online_exam') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('finance') && hasAnyPermission(['list-transport-fee','list-fee-group','list-fee-head','list-fee-allocation','list-fee-concession','list-account','list-income','list-expense','list-account-transfer'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="lime lighten-5"
        >
          <i class="fas fa-money-bill-alt fa-fw lime--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.finance') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-fee-group') && showMenu('fee_group')"
        to="/finance/fee/group"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.fee_group') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-fee-head') && showMenu('fee_head')"
        to="/finance/fee/head"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.fee_head') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-transport-fee') && showMenu('transport_fee')"
        to="/transport/fee"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.fee') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-fee-concession') && showMenu('fee_concession')"
        to="/finance/fee/concession"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.fee_concession') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-fee-allocation') && showMenu('fee_allocation')"
        to="/finance/fee/allocation"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.fee_allocation') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-account') && showMenu('account')"
        to="/finance/account"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.account') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-income') && showMenu('income')"
        to="/finance/transaction/income"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.income') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-expense') && showMenu('expense')"
        to="/finance/transaction/expense"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.expense') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-account-transfer') && showMenu('account_transfer')"
        to="/finance/transaction/account/transfer"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('finance.account_transfer') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('finance_report')"
        to="/finance/report"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.report') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('transport') && hasAnyPermission(['list-vehicle','list-vehicle-incharge','list-vehicle-document','list-vehicle-log','list-vehicle-service-record','list-vehicle-fuel'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="teal lighten-5"
        >
          <i class="fas fa-truck fa-fw teal--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.transport') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="showMenu('transport_route')"
        to="/transport/route"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.route') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle') && showMenu('vehicle')"
        to="/transport/vehicle"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.vehicle') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle-incharge') && showMenu('vehicle_incharge')"
        to="/transport/vehicle/incharge"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.vehicle_incharge') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle-document') && showMenu('vehicle_document')"
        to="/transport/vehicle/document"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.document') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle-fuel') && showMenu('vehicle_fuel')"
        to="/transport/vehicle/fuel"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.fuel') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle-log') && showMenu('vehicle_log')"
        to="/transport/vehicle/log"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.log') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-vehicle-service-record') && showMenu('vehicle_service_record')"
        to="/transport/vehicle/service/record"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('transport.service_record') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-transport-report') && showMenu('vehicle_report')"
        to="/transport/report"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.report') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('calendar') && hasAnyPermission(['list-holiday','list-event'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="light-blue lighten-5"
        >
          <i class="fas fa-calendar-alt fa-fw light-blue--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.calendar') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-holiday') && showMenu('holiday')"
        to="/calendar/holiday"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.holiday') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-event') && showMenu('event')"
        to="/calendar/event"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.event') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-birthday') && showMenu('celebration')"
        to="/calendar/celebration/birthday"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('calendar.celebration') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('resource') && hasAnyPermission(['list-assignment','list-notes','list-lesson-plan','list-syllabus'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="light-green lighten-5"
        >
          <i class="fas fa-folder fa-fw light-green--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('resource.resource') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-assignment') && showMenu('assignment')"
        to="/resource/assignment"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('resource.assignment') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-notes') && showMenu('notes')"
        to="/resource/notes"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('resource.notes') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-lesson-plan') && showMenu('lesson_plan')"
        to="/resource/lesson/plan"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('resource.lesson_plan') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('list-syllabus') && showMenu('syllabus')"
        to="/resource/syllabus"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('resource.syllabus') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('library') && hasAnyPermission(['list-book','issue-book','return-book'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="purple lighten-5"
        >
          <i class="fas fa-book fa-fw purple--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.library') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('list-book') && showMenu('book')"
        to="/library/book"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.book') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('issue-book') && showMenu('issue_book')"
        to="/library/issue"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.issue') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('return-book') && showMenu('return_book')"
        to="/library/return"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('library.return') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('inventory') && hasAnyPermission(['list-stock-category', 'list-stock-item','list-vendor','list-stock-purchase','list-stock-transfer'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="green lighten-5"
        >
          <i class="fas fa-box fa-fw green--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.inventory') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="showMenu('stock_category')"
        to="/inventory/stock/category"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.stock_category') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('stock_item')"
        to="/inventory/stock/item"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.stock_item') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('vendor')"
        to="/inventory/vendor"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.vendor') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('stock_purchase')"
        to="/inventory/stock/purchase"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.stock_purchase') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('stock_transfer')"
        to="/inventory/stock/transfer"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory.stock_transfer') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('stock_sale')"
        to="/inventory/stock/sale"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory_sale.stock_sale') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('stock_pos')"
        to="/inventory/stock/pos"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('inventory_sale.stock_pos') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('post') && hasPermission('list-article')"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="red lighten-5"
        >
          <i class="fas fa-newspaper fa-fw red--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('post.post') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="showMenu('post_feed')"
        to="/post/feed"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('post.feed') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('article')"
        to="/post/article"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('post.article') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('communication') && hasAnyPermission(['send-sms','send-email'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="light-green lighten-5"
        >
          <i class="fas fa-paper-plane fa-fw light-green--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('communication.communication') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="showMenu('communication_history')"
        to="/communication"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('communication.history') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('send-sms') && showMenu('send_sms')"
        to="/communication/sms"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('communication.sms') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('send-email') && showMenu('send_email')"
        to="/communication/email"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('communication.email') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('frontend') && hasPermission('configure-frontend')"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="teal lighten-5"
        >
          <i class="fas fa-suitcase fa-fw teal--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.frontend') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="showMenu('frontend_page')"
        to="/frontend/page"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.page') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('frontend_block')"
        to="/frontend/block"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.block') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="showMenu('frontend_menu')"
        to="/frontend/menu"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('frontend.menu') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('utility') && hasAnyPermission(['access-todo','access-configuration'])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="light-blue lighten-5"
        >
          <i class="fas fa-puzzle-piece fa-fw light-blue--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('utility.utility') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        v-if="hasPermission('access-todo') && showMenu('todo')"
        to="/utility/todo"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('utility.todo') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <template v-if="hasPermission('access-configuration')">
        <v-list-item
          v-if="showMenu('backup')"
          to="/utility/backup"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('utility.backup') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('ip_filter')"
          to="/utility/ip-filter"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('utility.ip_filter') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('activity_log')"
          to="/utility/activity-log"
          exact
        >
          <v-list-item-content>
            <v-list-item-title>{{ trans('utility.activity_log') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-list-group>
    <v-list-group
      v-show="moduleMenu && showMenu('amsl') && hasAnyPermission([
        'access-amsl-account-list',
        'access-amsl-employee-list-amsl',
        'access-amsl-employee-history-list',
        'access-amsl-expense-list',
        'access-amsl-income-list',
        'access-amsl-asset-list',
        'access-amsl-liability-list',
        'access-amsl-owner-equity-list',
        'access-amsl-ledger',
        'access-amsl-report',
      ])"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-avatar
          size="30"
          color="red lighten-5"
        >
          <i class="fas fa-cash-register fa-fw red--text darken-1--text" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl') }}</v-list-item-title>
        </v-list-item-content>
      </template>
      <v-list-item
        :to="{name:'amslDashboard'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('general.dashboard') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-account-list') && showMenu('amsl_account_list')"
        :to="{name:'accountList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_account_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-employee-list-amsl') && showMenu('amsl_employee_list_amsl')"
        :to="{name:'employeeListAmsl'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_employee_list_amsl') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        v-if="hasPermission('access-amsl-employee-history-list') && showMenu('amsl_employee_history_list')"
        :to="{name:'employeeHistoryList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_employee_history_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-expense-list') && showMenu('amsl_expense_list')"
        :to="{name:'expenseList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_expense_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        v-if="hasPermission('access-amsl-income-list') && showMenu('amsl_income_list')"
        :to="{name:'incomeList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_income_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-asset-list') && showMenu('amsl_asset_list')"
        :to="{name:'assetList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_asset_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        v-if="hasPermission('access-amsl-liability-list') && showMenu('amsl_liability_list')"
        :to="{name:'liabilityList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_liability_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-owner-equity-list') && showMenu('amsl_owner_equity_list')"
        :to="{name:'ownerEquityList'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.amsl_owner_equity_list') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-ledger') && showMenu('amsl_ledger')"
        :to="{name:'amslLedger'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.ledger') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item
        v-if="hasPermission('access-amsl-report') && showMenu('amsl_report')"
        :to="{name:'amslReport'}"
        exact
      >
        <v-list-item-content>
          <v-list-item-title>{{ trans('amsl.report') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
    <v-list-item
      v-show="moduleMenu && hasPermission('access-configuration')"
      to="/configuration"
      exact
    >
      <v-list-item-avatar
        size="30"
        color="deep-orange lighten-5"
      >
        <i class="fas fa-cogs fa-fw deep-orange--text darken-1--text" />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ trans('configuration.configuration') }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  data: () => ({
  }),
  computed: {
    configMenu() {
      return this.$route.meta.menu == 'configuration' ? true : false;
    },
    moduleConfigMenu() {
      return this.$route.meta.menu == 'module-configuration' ? true : false;
    },
    moduleMenu() {
      return this.$route.meta.menu != 'configuration' && this.$route.meta.menu != 'module-configuration' ? true : false;
    }
  },
  methods: {
    hasPermission(permission) {
      return helper.hasPermission(permission);
    },
    hasAnyPermission(permissions) {
      return helper.hasAnyPermission(permissions);
    },
    hasRole(role) {
      return helper.hasRole(role);
    },
    hasNotAnyRole(role) {
      return helper.hasNotAnyRole(role);
    },
    getConfig(config) {
      return helper.getConfig(config);
    },
    showMenu(menu) {
      let menus = helper.getConfig('menu');
      return (Array.isArray(menus) && menus.findIndex(o => o === menu) >= 0) ? true : false;
    }
  }
}
</script>
