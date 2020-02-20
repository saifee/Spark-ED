<template>
  <v-toolbar-items>
    <v-tooltip bottom>
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/dashboard"
          exact
          v-on="on"
        >
          <i class="fas fa-home fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('general.dashboard') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/basic"
          exact
          v-on="on"
        >
          <i class="fas fa-cog fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.basic_configuration') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/social"
          exact
          v-on="on"
        >
          <i class="fas fa-share-alt fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.social_network') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/system"
          exact
          v-on="on"
        >
          <i class="fas fa-cogs fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.system_configuration') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/mail"
          exact
          v-on="on"
        >
          <i class="fas fa-envelope fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.mail_configuration') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration') && getConfig('multilingual')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/locale"
          exact
          v-on="on"
        >
          <i class="fas fa-globe fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.locale') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration') && hasRole('admin')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/role"
          exact
          v-on="on"
        >
          <i class="fas fa-users fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.role') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration') && hasRole('admin')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/permission"
          exact
          v-on="on"
        >
          <i class="fas fa-key fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.permission') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/sms"
          exact
          v-on="on"
        >
          <i class="fas fa-comment fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.sms') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/payment/gateway"
          exact
          v-on="on"
        >
          <i class="fas fa-credit-card fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('finance.payment_gateway') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/authentication"
          exact
          v-on="on"
        >
          <i class="fas fa-sign-in-alt fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('auth.authentication') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/menu"
          exact
          v-on="on"
        >
          <i class="fas fa-ellipsis-h fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.menu') }}</span>
    </v-tooltip>
    <v-tooltip
      v-if="configMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/module"
          exact
          v-on="on"
        >
          <i class="fas fa-boxes fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.module_configuration') }}</span>
    </v-tooltip>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-user-circle fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/reception/enquiry/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.enquiry_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/reception/enquiry/source">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.enquiry_source') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/reception/visiting/purpose">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.visiting_purpose') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/reception/calling/purpose">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.calling_purpose') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/reception/complaint/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.complaint_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-school fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/academic/course/group">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.course_group') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/academic/institute">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.institute_other') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/academic/certificate/template">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.certificate_template') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/academic/id-card/template">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.id_card_template') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-money-bill-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/finance/transaction/category">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.transaction_category') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/finance/payment/method">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.payment_method') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-graduation-cap fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/student">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/student/group">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.student_group') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/student/document/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.document_type_only') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-gem fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/behaviour/skill">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('behaviour.skill') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-file-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/exam/term">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.term') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/exam/assessment">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.assessment') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/exam/observation">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.observation') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/exam/grade">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.grade') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-user-tie fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/employee">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/category">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.category') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/designation">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.designation') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/department">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.department') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/group">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.employee_group') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/document/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.document_type_only') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/leave/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.leave_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/attendance/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.attendance_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/employee/pay/head">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.pay_head') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-truck fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/transport/vehicle/document/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.vehicle_document_type_only') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/transport/vehicle/fuel/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.vehicle_fuel_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/transport/vehicle/service/center">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.vehicle_service_center_only') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-book fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/library">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('general.general') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/library/book/author">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book_author') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/library/book/language">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book_language') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/library/book/topic">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book_topic') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/library/book/publisher">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book_publisher') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/library/book/condition">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book_condition') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-calendar-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/calendar/event/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('calendar.event_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-newspaper fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/post/article/type">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('post.article_type') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-building fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/asset/building">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('asset.building') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/asset/room">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('asset.room') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-suitcase fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/frontend/index">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('frontend.frontend') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-tooltip
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration/custom-field"
          aria-expanded="false"
          v-on="on"
        >
          <i class="fas fa-cubes fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.custom_field') }}</span>
    </v-tooltip>
    <v-menu
      v-if="moduleConfigMenu && hasPermission('access-configuration')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-align-justify fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item to="/configuration/misc/religion">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('misc.religion') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/misc/caste">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('misc.caste') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/misc/category">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('misc.category') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/configuration/misc/blood/group">
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('misc.blood_group') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-tooltip
      v-if="moduleMenu && showMenu('institute_document') && hasPermission('list-institute-document')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/institute/document"
          exact
          v-on="on"
        >
          <i class="fas fa-file fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('institute.document') }}</span>
    </v-tooltip>
    <v-menu
      v-if="moduleMenu && showMenu('reception') && hasAnyPermission(['list-enquiry','list-visitor-log','list-postal-record','list-call-log','list-complaint','list-gate-pass'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-user-circle fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-enquiry') && showMenu('enquiry')"
          to="/reception/enquiry"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.admission_enquiry') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-visitor-log') && showMenu('visitor_log')"
          to="/reception/visitor/log"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.visitor_log') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-call-log') && showMenu('call_log')"
          to="/reception/call/log"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.call_log') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-postal-record') && showMenu('postal_record')"
          to="/reception/postal/record"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.postal_record') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-complaint') && showMenu('complaint')"
          to="/reception/complaint"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.complaint') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-gate-pass') && showMenu('gate_pass')"
          to="/reception/gate/pass"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.gate_pass') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-visitor-message') && showMenu('visitor_message')"
          to="/reception/visitor/message"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('reception.visitor_message') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('academic') && hasAnyPermission(['list-academic-session','list-course','list-batch','list-class-teacher','list-subject','list-subject-teacher','list-class-timing','list-timetable'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-school fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-academic-session') && showMenu('academic_session')"
          to="/academic/session"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.academic_session') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-course') && showMenu('course')"
          to="/academic/course"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.course') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-batch') && showMenu('batch')"
          to="/academic/batch"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.batch') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-class-teacher') && showMenu('class_teacher')"
          to="/academic/class/teacher"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.class_teacher') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-subject') && showMenu('subject')"
          to="/academic/subject"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.subject') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-subject-teacher') && showMenu('subject_teacher')"
          to="/academic/subject/teacher"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.subject_teacher') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-class-timing') && showMenu('class_timing')"
          to="/academic/class/timing"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.class_timing') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-timetable') && showMenu('timetable')"
          to="/academic/timetable"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.timetable') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-certificate') && showMenu('certificate')"
          to="/academic/certificate"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('academic.certificate') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('student') && hasAnyPermission(['list-registration','list-student','edit-roll-number','generate-student-id-card','list-student-attendance','promote-student','terminate-student'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-graduation-cap fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('import-student') && showMenu('student_import')"
          to="/student/import"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.import') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-registration') && showMenu('registration')"
          to="/student/registration"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.registration') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="(hasPermission('list-student') || hasPermission('list-class-teacher-wise-student')) && showMenu('student_list')"
          to="/student/admission"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.admission') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="(hasPermission('list-student') || hasPermission('list-class-teacher-wise-student')) && showMenu('student_behaviour')"
          to="/student/behaviour"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('behaviour.behaviour') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('edit-roll-number') && showMenu('roll_number')"
          to="/student/roll/number"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.roll_number') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('generate-student-id-card') && showMenu('student_id_card')"
          to="/student/id-card"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.id_card') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('edit-student') && showMenu('student_image_upload')"
          to="/student/image"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.image_upload') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-student-attendance') && showMenu('student_attendance')"
          to="/student/attendance"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.attendance') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('promote-student') && showMenu('promotion')"
          to="/student/promotion"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.promotion') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('terminate-student') && showMenu('termination')"
          to="/student/termination"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.termination') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('edit-student') && showMenu('student_parent')"
          to="/student/parent"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('student.parent') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('employee') && hasAnyPermission(['list-employee', 'generate-employee-id-card'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-user-tie fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('import-employee') && showMenu('employee_import')"
          to="/employee/import"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.import') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('employee_list')"
          to="/employee/list"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.employee_list') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('generate-employee-id-card') && showMenu('employee_id_card')"
          to="/employee/id-card"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.id_card') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('employee_attendance')"
          to="/employee/attendance"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.attendance') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('employee_leave')"
          to="/employee/leave"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.leave') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('employee_payroll')"
          to="/employee/payroll"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('employee.payroll') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('exam') && hasAnyPermission(['list-exam-schedule','list-exam-mark','access-exam-report','access-class-teacher-wise-exam-report','list-online-exam'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-file-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-exam-schedule') && showMenu('exam_schedule')"
          to="/exam/schedule"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.schedule') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-exam-mark') && showMenu('exam_record_mark')"
          to="/exam/record"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.record') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasAnyPermission(['access-exam-report','access-class-teacher-wise-exam-report']) && showMenu('exam_report_card')"
          to="/exam/report"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.report') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <template v-if="hasNotAnyRole(['student','parent']) && showMenu('exam_topper_report')">
          <v-list-item
            v-if="hasAnyPermission(['access-exam-report','access-class-teacher-wise-exam-report'])"
            to="/exam/report/topper"
          >
            <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('exam.topper_report') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
        <v-list-item
          v-if="hasPermission('list-online-exam') && showMenu('online_exam')"
          to="/online-exam"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('exam.online_exam') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('finance') && hasAnyPermission(['list-transport-fee','list-fee-group','list-fee-head','list-fee-allocation','list-fee-concession','list-account','list-income','list-expense','list-account-transfer'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-money-bill-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-fee-group') && showMenu('fee_group')"
          to="/finance/fee/group"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.fee_group') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-fee-head') && showMenu('fee_head')"
          to="/finance/fee/head"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.fee_head') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-transport-fee') && showMenu('transport_fee')"
          to="/transport/fee"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.fee') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-fee-concession') && showMenu('fee_concession')"
          to="/finance/fee/concession"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.fee_concession') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-fee-allocation') && showMenu('fee_allocation')"
          to="/finance/fee/allocation"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.fee_allocation') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-account') && showMenu('account')"
          to="/finance/account"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.account') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-income') && showMenu('income')"
          to="/finance/transaction/income"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.income') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-expense') && showMenu('expense')"
          to="/finance/transaction/expense"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.expense') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-account-transfer') && showMenu('account_transfer')"
          to="/finance/transaction/account/transfer"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('finance.account_transfer') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('finance_report')"
          to="/finance/report"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('general.report') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('transport') && hasAnyPermission(['list-vehicle','list-vehicle-incharge','list-vehicle-document','list-vehicle-log','list-vehicle-service-record','list-vehicle-fuel'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-truck fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="showMenu('transport_route')"
          to="/transport/route"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.route') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle') && showMenu('vehicle')"
          to="/transport/vehicle"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.vehicle') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle-incharge') && showMenu('vehicle_incharge')"
          to="/transport/vehicle/incharge"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.vehicle_incharge') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle-document') && showMenu('vehicle_document')"
          to="/transport/vehicle/document"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.document') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle-fuel') && showMenu('vehicle_fuel')"
          to="/transport/vehicle/fuel"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.fuel') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle-log') && showMenu('vehicle_log')"
          to="/transport/vehicle/log"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.log') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-vehicle-service-record') && showMenu('vehicle_service_record')"
          to="/transport/vehicle/service/record"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('transport.service_record') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('access-transport-report') && showMenu('vehicle_report')"
          to="/transport/report"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('general.report') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('calendar') && hasAnyPermission(['list-holiday','list-event'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-calendar-alt fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-holiday') && showMenu('holiday')"
          to="/calendar/holiday"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('calendar.holiday') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-event') && showMenu('event')"
          to="/calendar/event"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('calendar.event') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-birthday') && showMenu('celebration')"
          to="/calendar/celebration/birthday"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('calendar.celebration') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('resource') && hasAnyPermission(['list-assignment','list-notes','list-lesson-plan','list-syllabus'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-folder fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-assignment') && showMenu('assignment')"
          to="/resource/assignment"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('resource.assignment') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-notes') && showMenu('notes')"
          to="/resource/notes"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('resource.notes') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-lesson-plan') && showMenu('lesson_plan')"
          to="/resource/lesson/plan"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('resource.lesson_plan') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('list-syllabus') && showMenu('syllabus')"
          to="/resource/syllabus"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('resource.syllabus') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('library') && hasAnyPermission(['list-book','issue-book','return-book'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-book fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('list-book') && showMenu('book')"
          to="/library/book"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.book') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('issue-book') && showMenu('issue_book')"
          to="/library/issue"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.issue') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('return-book') && showMenu('return_book')"
          to="/library/return"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('library.return') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('inventory') && hasAnyPermission(['list-stock-category', 'list-stock-item','list-vendor','list-stock-purchase','list-stock-transfer'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-box fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="showMenu('stock_category')"
          to="/inventory/stock/category"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory.stock_category') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('stock_item')"
          to="/inventory/stock/item"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory.stock_item') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('vendor')"
          to="/inventory/vendor"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory.vendor') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('stock_purchase')"
          to="/inventory/stock/purchase"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory.stock_purchase') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('stock_transfer')"
          to="/inventory/stock/transfer"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory.stock_transfer') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('stock_sale') && getConfig('made') === 'saudi'"
          to="/inventory/stock/sale"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('inventory_sale.stock_sale') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('post') && hasPermission('list-article')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-newspaper fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="showMenu('post_feed')"
          to="/post/feed"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('post.feed') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('article')"
          to="/post/article"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('post.article') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('communication') && hasAnyPermission(['send-sms','send-email'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-paper-plane fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="showMenu('communication_history')"
          to="/communication"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('communication.history') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('send-sms') && showMenu('send_sms')"
          to="/communication/sms"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('communication.sms') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="hasPermission('send-email') && showMenu('send_email')"
          to="/communication/email"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('communication.email') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('frontend') && hasPermission('configure-frontend')"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-suitcase fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="showMenu('frontend_page')"
          to="/frontend/page"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('frontend.page') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('frontend_block')"
          to="/frontend/block"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('frontend.block') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          v-if="showMenu('frontend_menu')"
          to="/frontend/menu"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('frontend.menu') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu
      v-if="moduleMenu && showMenu('utility') && hasAnyPermission(['access-todo','access-configuration'])"
      offset-y
    >
      <template v-slot:activator="{ on }">
        <v-btn
          text
          v-on="on"
        >
          <i class="fas fa-puzzle-piece fa-fw" />
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-if="hasPermission('access-todo') && showMenu('todo')"
          to="/utility/todo"
        >
          <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ trans('utility.todo') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <template v-if="hasPermission('access-configuration')">
          <v-list-item
            v-if="showMenu('backup')"
            to="/utility/backup"
          >
            <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('utility.backup') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            v-if="showMenu('ip_filter')"
            to="/utility/ip-filter"
          >
            <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('utility.ip_filter') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            v-if="showMenu('activity_log')"
            to="/utility/activity-log"
          >
            <v-list-item-action><i class="fas fa-angle-double-right" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('utility.activity_log') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>
    <v-tooltip
      v-if="moduleMenu && hasPermission('access-configuration')"
      bottom
    >
      <template v-slot:activator="{ on }">
        <v-btn
          icon
          to="/configuration"
          exact
          v-on="on"
        >
          <i class="fas fa-cogs fa-fw" />
        </v-btn>
      </template>
      <span>{{ trans('configuration.configuration') }}</span>
    </v-tooltip>
  </v-toolbar-items>
</template>
<script>
export default {
  data: () => ({}),
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
